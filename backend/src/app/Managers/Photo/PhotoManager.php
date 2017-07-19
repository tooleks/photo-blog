<?php

namespace App\Managers\Photo;

use Carbon\Carbon;
use Closure;
use App\DataProviders\Photo\Criterias\HasSearchPhrase;
use App\DataProviders\Photo\Criterias\HasTagWithValue;
use App\DataProviders\Photo\Criterias\IsPublished;
use App\DataProviders\Photo\Contracts\PhotoDataProvider;
use App\DataProviders\Photo\Criterias\WhereCreatedByUserId;
use App\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use App\Models\Photo;
use App\Services\Photo\Contracts\ExifFetcherService;
use App\Services\Photo\Contracts\ThumbnailsGeneratorService;
use App\Services\Trash\Contracts\TrashServiceException;
use App\Services\Trash\Contracts\TrashService;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Lib\DataProvider\Criterias\SortByCreatedAt;
use Lib\DataProvider\Criterias\Take;
use Lib\DataProvider\Criterias\WhereCreatedAtGreaterThan;
use Lib\DataProvider\Criterias\WhereUpdatedAtLessThan;
use Throwable;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class PhotoManager.
 *
 * @package App\Managers\Photo
 */
class PhotoManager implements PhotoManagerContract
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var PhotoDataProvider
     */
    private $photoDataProvider;

    /**
     * @var TrashService
     */
    private $trashService;

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
     * PhotoManager constructor.
     *
     * @param Storage $storage
     * @param PhotoDataProvider $photoDataProvider
     * @param TrashService $trashService
     * @param ExifFetcherService $exifFetcher
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(
        Storage $storage,
        PhotoDataProvider $photoDataProvider,
        TrashService $trashService,
        ExifFetcherService $exifFetcher,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        AvgColorPicker $avgColorPicker
    )
    {
        $this->storage = $storage;
        $this->photoDataProvider = $photoDataProvider;
        $this->trashService = $trashService;
        $this->exifFetcher = $exifFetcher;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): Photo
    {
        return $this->photoDataProvider->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getPublishedById(int $id): Photo
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getNotPublishedById(int $id): Photo
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getLastPublished(int $take): Collection
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->applyCriteria(new Take($take))
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function paginateOverLastPublished(int $page, int $perPage, array $query = []): AbstractPaginator
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteriaWhen(isset($query['tag']), new HasTagWithValue($query['tag'] ?? null))
            ->applyCriteriaWhen(isset($query['search_phrase']), new HasSearchPhrase($query['search_phrase'] ?? null))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->paginate($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback): void
    {
        $this->photoDataProvider->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function eachPublished(Closure $callback): void
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function eachCreatedByUserId(Closure $callback, int $createdByUserId): void
    {
        $this->photoDataProvider
            ->applyCriteria(new WhereCreatedByUserId($createdByUserId))
            ->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function eachNotPublishedOlderThanWeek(Closure $callback): void
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->applyCriteria(new WhereUpdatedAtLessThan((new Carbon)->addWeek('-1')))
            ->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function existsPublishedOlderThanWeek(): bool
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria(new WhereCreatedAtGreaterThan((new Carbon)->addWeek('-1')))
            ->exists();
    }

    /**
     * @inheritdoc
     */
    public function createNotPublishedWithFile(UploadedFile $file, int $createdByUserId = null, array $attributes = [], array $options = []): Photo
    {
        $photo = new Photo;

        $photo->is_published = false;
        $photo->created_by_user_id = $createdByUserId;

        $this->saveWithFile($photo, $file, $attributes, $options);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function save(Photo $photo, array $attributes = [], array $options = []): void
    {
        $this->photoDataProvider->save($photo, $attributes, $options);
    }

    /**
     * @inheritdoc
     */
    public function publish(Photo $photo, array $attributes = [], array $options = []): void
    {
        $photo->is_published = true;

        $this->photoDataProvider->save($photo, $attributes, $options);
    }

    /**
     * @inheritdoc
     */
    public function saveWithFile(Photo $photo, UploadedFile $file, array $attributes = [], array $options = []): void
    {
        $oldDirectoryPath = dirname($photo->path);
        $newDirectoryPath = sprintf('%s/%s', config('main.storage.path.photos'), str_random(10));

        try {
            $photo->path = $this->storage->put($newDirectoryPath, $file);
            $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath(
                $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path
            );
            $attributes = array_merge($attributes, [
                'exif' => $this->exifFetcher->fetchFromUploadedFile($file),
                'thumbnails' => $this->thumbnailsGenerator->generateByFilePath($photo->path),
            ]);
            $options = array_merge($options, [
                'with' => ['exif', 'thumbnails'],
            ]);
            $this->photoDataProvider->save($photo, $attributes, $options);
            $this->trashService->moveIfExists($oldDirectoryPath);
        } catch (TrashServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashService->restoreIfExists($oldDirectoryPath);
            $this->trashService->moveIfExists($newDirectoryPath);
            throw $e;
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(Photo $photo): void
    {
        $directoryPath = dirname($photo->path);

        try {
            $this->trashService->moveIfExists($directoryPath);
            $this->photoDataProvider->delete($photo);
        } catch (TrashServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashService->restoreIfExists($directoryPath);
            throw $e;
        }
    }
}
