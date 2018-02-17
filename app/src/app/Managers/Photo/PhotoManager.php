<?php

namespace App\Managers\Photo;

use function App\Util\str_unique;
use App\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use App\Models\Photo;
use App\Services\Photo\Contracts\ExifFetcherService;
use App\Services\Photo\Contracts\ThumbnailsGeneratorService;
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
     * Save exif relation records.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    private function saveExif(Photo $photo, array $attributes): void
    {
        $photo->exif()->delete();

        $photo->exif()->create($attributes);

        $photo->load('exif');
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

        $photo->load('thumbnails');
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes = []): Photo
    {
        $attributes = $this->validator->validateForCreate($attributes);
        // Generate average color of the image.
        $attributes['avg_color'] = $this->avgColorPicker->getImageAvgHex($attributes['file']->getPathname());
        // Move image from the temporary location to the permanent location.
        $attributes['path'] = $this->storage->put(sprintf('photos/%s', str_unique(20)), $attributes['file']);
        // Fetch the EXIF information from the image.
        $attributes['exif'] = $this->exifFetcher->fetch($attributes['file']);
        // Generate thumbnails from the image.
        $attributes['thumbnails'] = $this->thumbnailsGenerator->generate($attributes['path']);

        $photo = (new Photo)->fill($attributes);

        $this->database->transaction(function () use ($photo, $attributes) {
            $photo->save();
            $this->saveExif($photo, $attributes['exif']);
            $this->saveThumbnails($photo, $attributes['thumbnails']);
        });

        return $photo;
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
