<?php

namespace App\Managers\Photo;

use App\Models\Photo;
use App\Services\Image\Contracts\ImageProcessor;
use Core\Contracts\LocationManager;
use Core\Contracts\PhotoManager;
use Core\Entities\PhotoEntity;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\ConnectionInterface as Database;
use function App\Util\str_unique;

/**
 * Class ARPhotoManager.
 *
 * @package App\Managers\Photo
 */
class ARPhotoManager implements PhotoManager
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
     * @var LocationManager
     */
    private $locationManager;

    /**
     * @var ImageProcessor
     */
    private $imageProcessor;

    /**
     * @var PhotoValidator
     */
    private $validator;

    /**
     * ARPhotoManager constructor.
     *
     * @param Database $database
     * @param Storage $storage
     * @param LocationManager $locationManager
     * @param ImageProcessor $imageProcessor
     * @param PhotoValidator $validator
     */
    public function __construct(
        Database $database,
        Storage $storage,
        LocationManager $locationManager,
        ImageProcessor $imageProcessor,
        PhotoValidator $validator
    )
    {
        $this->database = $database;
        $this->storage = $storage;
        $this->locationManager = $locationManager;
        $this->imageProcessor = $imageProcessor;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): PhotoEntity
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $attributes['path'] = $this->storage->put(sprintf('photos/%s', str_unique(20)), $attributes['file']);

        $photo = (new Photo)->fill($attributes);

        $this->imageProcessor->open($attributes['path']);
        $photo->avg_color = $this->imageProcessor->getAvgColor();
        $photo->metadata = $this->imageProcessor->getMetadata();
        $thumbnails = $this->imageProcessor->createThumbnails();
        $this->imageProcessor->close();

        $this->database->transaction(function () use ($photo, $attributes, $thumbnails) {
            if (isset($attributes['location'])) {
                $photo->location_id = $this->locationManager->create($attributes['location'])->getId();
            }
            $photo->save();
            $photo->thumbnails()->detach();
            collect($thumbnails)->each(function (array $attributes) use ($photo) {
                $photo->thumbnails()->create($attributes);
            });
        });

        return $photo->loadEntityRelations()->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function updateById($id, array $attributes): PhotoEntity
    {
        $attributes = $this->validator->validateForUpdate($attributes);

        /** @var Photo $photo */
        $photo = (new Photo)
            ->newQuery()
            ->findOrFail($id)
            ->fill($attributes);

        $this->database->transaction(function () use ($photo, $attributes) {
            $photo->location_id = $this->locationManager->create($attributes['location'])->getId();
            $photo->save();
        });

        return $photo->loadEntityRelations()->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): PhotoEntity
    {
        /** @var Photo $photo */
        $photo = (new Photo)
            ->newQuery()
            ->withEntityRelations()
            ->findOrFail($id);

        return $photo->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): PhotoEntity
    {
        /** @var Photo $photo */
        $photo = (new Photo)
            ->newQuery()
            ->withEntityRelations()
            ->findOrFail($id);

        $this->database->transaction(function () use ($photo) {
            $photo->delete();
            $this->storage->deleteDirectory($photo->toEntity()->getDirPath());
        });

        return $photo->toEntity();
    }
}
