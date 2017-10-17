<?php

namespace App\Managers\Photo;

use function App\Util\str_unique;
use App\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use App\Models\Builders\PhotoBuilder;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\Thumbnail;
use App\Services\Photo\Contracts\ExifFetcherService;
use App\Services\Photo\Contracts\ThumbnailsGeneratorService;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\ConnectionInterface as DbConnection;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as ValidatorFactory;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class PhotoManager.
 *
 * @package App\Managers\Photo
 */
class PhotoManager implements PhotoManagerContract
{
    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var ExifFetcherService
     */
    private $exifFetcher;

    /**
     * @var ThumbnailsGeneratorService
     */
    private $thumbnailsGenerator;

    /**
     * @var AvgColorPicker
     */
    private $avgColorPicker;

    /**
     * @var PhotoValidator
     */
    private $validator;

    /**
     * PhotoManager constructor.
     *
     * @param ValidatorFactory $validatorFactory
     * @param DbConnection $dbConnection
     * @param Storage $storage
     * @param ExifFetcherService $exifFetcher
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param AvgColorPicker $avgColorPicker
     * @param PhotoValidator $validator
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        DbConnection $dbConnection,
        Storage $storage,
        ExifFetcherService $exifFetcher,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        AvgColorPicker $avgColorPicker,
        PhotoValidator $validator
    )
    {
        $this->validatorFactory = $validatorFactory;
        $this->dbConnection = $dbConnection;
        $this->storage = $storage;
        $this->exifFetcher = $exifFetcher;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->avgColorPicker = $avgColorPicker;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): Photo
    {
        $photo = (new Photo)
            ->newQuery()
            ->findOrFail($id);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getPublishedById(int $id): Photo
    {
        $photo = (new Photo)
            ->newQuery()
            ->wherePublished()
            ->findOrFail($id);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getUnpublishedById(int $id): Photo
    {
        $photo = (new Photo)
            ->newQuery()
            ->whereUnpublished()
            ->findOrFail($id);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getNewlyPublished(int $take = 10, int $skip = 0): Collection
    {
        $photos = (new Photo)
            ->newQuery()
            ->wherePublished()
            ->withExif()
            ->withThumbnails()
            ->withTags()
            ->orderByCreatedAtDesc()
            ->take($take)
            ->skip($skip)
            ->get();

        return $photos;
    }

    /**
     * @inheritdoc
     */
    public function paginateOverNewlyPublished(int $page, int $perPage, array $filters = [])
    {
        $query = (new Photo)
            ->newQuery()
            ->wherePublished()
            ->withExif()
            ->withThumbnails()
            ->withTags()
            ->when(isset($filters['tag']), function (PhotoBuilder $query) use ($filters) {
                $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PhotoBuilder $query) use ($filters) {
                $query->searchPhrase($filters['search_phrase']);
            })
            ->orderByCreatedAtDesc();

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback): void
    {
        (new Photo)
            ->newQuery()
            ->chunk(10, function (Collection $collection) use ($callback) {
                $collection->each($callback);
            });
    }

    /**
     * @inheritdoc
     */
    public function eachPublished(Closure $callback): void
    {
        (new Photo)
            ->newQuery()
            ->wherePublished()
            ->chunk(10, function (Collection $collection) use ($callback) {
                $collection->each($callback);
            });
    }

    /**
     * @inheritdoc
     */
    public function eachUnpublishedGreaterThanWeekAgo(Closure $callback): void
    {
        (new Photo)
            ->newQuery()
            ->whereUnpublished()
            ->whereUpdatedAtLessThan(Carbon::now()->addWeek('-1'))
            ->chunk(10, function (Collection $collection) use ($callback) {
                $collection->each($callback);
            });
    }

    /**
     * @inheritdoc
     */
    public function existsPublishedLessThanWeekAgo(): bool
    {
        return (new Photo)
            ->newQuery()
            ->wherePublished()
            ->whereCreatedAtGreaterThan(Carbon::now()->addWeek('-1'))
            ->exists();
    }

    /**
     * @inheritdoc
     */
    public function createByFile(array $attributes = []): Photo
    {
        $attributes = $this->validator->validateForCreateByFile($attributes);

        $photo = (new Photo)->fill($attributes);

        $this->saveByFile($photo, $attributes);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function saveByFile(Photo $photo, array $attributes = []): void
    {
        $attributes = $this->validator->validateForSaveByFile($attributes);

        $attributes['avg_color'] = $this->avgColorPicker->getImageAvgHex($attributes['file']->getPathname());
        $attributes['path'] = $this->storage->put(sprintf('photos/%s', str_unique(20)), $attributes['file']);
        $attributes['exif'] = $this->exifFetcher->fetch($attributes['file']);
        $attributes['thumbnails'] = $this->thumbnailsGenerator->generate($attributes['path']);

        $photo->fill($attributes);

        $this->dbConnection->transaction(function () use ($photo, $attributes) {
            $photo->save();
            if (isset($attributes['exif'])) {
                $this->saveExif($photo, $attributes['exif']);
            }
            if (isset($attributes['thumbnails'])) {
                $this->saveThumbnails($photo, $attributes['thumbnails']);
            }
        });
    }

    public function generateThumbnails(Photo $photo): void
    {
        $thumbnails = $this->thumbnailsGenerator->generate($photo->path);

        $this->dbConnection->transaction(function () use ($photo, $thumbnails) {
            $photo->save();
            $this->saveThumbnails($photo, $thumbnails);
        });
    }

    /**
     * @inheritdoc
     */
    public function saveByAttributes(Photo $photo, array $attributes = []): void
    {
        $attributes = $this->validator->validateForSaveByAttributes($photo, $attributes);

        $photo->fill($attributes);

        $this->dbConnection->transaction(function () use ($photo, $attributes) {
            $photo->save();
            if (isset($attributes['tags'])) {
                $this->saveTags($photo, $attributes['tags']);
            }
        });
    }

    /**
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    private function saveExif(Photo $photo, array $attributes): void
    {
        $photo->exif()->delete();

        $photo->exif = $photo->exif()->create($attributes);
    }

    /**
     * @param Photo $photo
     * @param array $records
     * @return void
     */
    private function saveThumbnails(Photo $photo, array $records): void
    {
        $photo->thumbnails()->detach();

        $photo->thumbnails = collect($records)->map(function (array $attributes) use ($photo) {
            return $photo->thumbnails()->create($attributes);
        });

//        (new Thumbnail)->newQuery()->whereHasNoPhotos()->delete();
    }

    /**
     * @param Photo $photo
     * @param array $records
     * @return void
     */
    private function saveTags(Photo $photo, array $records): void
    {
        $photo->tags = collect($records)->map(function (array $attributes) {
            return (new Tag)->newQuery()->firstOrCreate(['value' => $attributes['value']]);
        });

        $photo->tags()->sync($photo->tags->pluck('id'));

//        (new Tag)->newQuery()->whereHasNoPhotos()->delete();
    }

    /**
     * @inheritdoc
     */
    public function delete(Photo $photo): void
    {
        $this->dbConnection->transaction(function () use ($photo) {
            $photo->delete();
            (new Tag)->newQuery()->whereHasNoPhotos()->delete();
            (new Thumbnail)->newQuery()->whereHasNoPhotos()->delete();
            if ($photo->directory_path && $this->storage->exists($photo->directory_path)) {
                $this->storage->deleteDirectory($photo->directory_path);
            }
        });
    }
}
