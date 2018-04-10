<?php

namespace App\Managers\Photo;

use function App\Util\str_unique;
use App\ValueObjects\Coordinates;
use App\Models\Location;
use App\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use App\Models\Photo;
use App\Services\Photo\Contracts\ExifFetcherService;
use App\Services\Photo\Contracts\ThumbnailsGeneratorService;
use App\ValueObjects\Latitude;
use App\ValueObjects\Longitude;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\ConnectionInterface as Database;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class PhotoManager.
 *
 * @package App\Managers\Photo
 */
class PhotoManager implements PhotoManagerContract
{
    /**
     * @var Database
     */
    private $database;

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
     * @param Database $database
     * @param Storage $storage
     * @param ExifFetcherService $exifFetcher
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param AvgColorPicker $avgColorPicker
     * @param PhotoValidator $validator
     */
    public function __construct(
        Database $database,
        Storage $storage,
        ExifFetcherService $exifFetcher,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        AvgColorPicker $avgColorPicker,
        PhotoValidator $validator
    )
    {
        $this->database = $database;
        $this->storage = $storage;
        $this->exifFetcher = $exifFetcher;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->avgColorPicker = $avgColorPicker;
        $this->validator = $validator;
    }

    /**
     * Create location record.
     *
     * @param array $attributes
     * @return Location
     */
    private function createLocation(array $attributes): Location
    {
        ['latitude' => $latitude, 'longitude' => $longitude] = $attributes;

        $coordinates = new Coordinates(new Latitude($latitude), new Longitude($longitude));

        $location = (new Location)->fill(['coordinates' => $coordinates]);

        $location->save();

        return $location;
    }

    /**
     * Save exif relation record.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    private function saveExif(Photo $photo, array $attributes): void
    {
        $photo->exif()->delete();

        $photo->exif()->create($attributes);
    }

    /**
     * Save thumbnails relation records.
     *
     * @param Photo $photo
     * @param array $rawThumbnails
     * @return void
     */
    private function saveThumbnails(Photo $photo, array $rawThumbnails): void
    {
        $photo->thumbnails()->detach();

        collect($rawThumbnails)->each(function (array $attributes) use ($photo) {
            $photo->thumbnails()->create($attributes);
        });
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes = []): Photo
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $attributes['avg_color'] = $this->avgColorPicker->getImageAvgHex($attributes['file']->getPathname());
        $attributes['exif'] = $this->exifFetcher->fetch($attributes['file']);
        $attributes['path'] = $this->storage->put(sprintf('photos/%s', str_unique(20)), $attributes['file']);
        $attributes['thumbnails'] = $this->thumbnailsGenerator->generate($attributes['path']);

        $photo = (new Photo)->fill($attributes);

        $this->database->transaction(function () use ($photo, $attributes) {
            if (isset($attributes['location'])) {
                $location = $this->createLocation($attributes['location']);
                $photo->fill(['location_id' => $location->id]);
            }
            $photo->save();
            $this->saveExif($photo, $attributes['exif']);
            $this->saveThumbnails($photo, $attributes['thumbnails']);
        });

        $photo->load('location', 'exif', 'thumbnails');

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function update(Photo $photo, array $attributes): void
    {
        $attributes = $this->validator->validateForUpdate($attributes);

        $this->database->transaction(function () use ($photo, $attributes) {
            $location = $this->createLocation($attributes['location']);
            $photo->fill(array_merge($attributes, ['location_id' => $location->id]));
            $photo->save();
        });

        $photo->load('location', 'exif', 'thumbnails');
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
    public function delete(Photo $photo): void
    {
        $this->database->transaction(function () use ($photo) {
            $photo->delete();
            if ($this->storage->exists(dirname($photo->path))) {
                $this->storage->deleteDirectory(dirname($photo->path));
            }
        });
    }
}
