<?php

namespace Core\DAL\Repositories;

use Throwable;
use Core\DAL\Models\Photo;
use Core\DAL\Repositories\Contracts\PhotoRepository as PhotoRepositoryContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class PhotoRepository.
 *
 * @property ConnectionInterface dbConnection
 * @package Core\DAL\Repositories\Contracts
 */
class PhotoRepository implements PhotoRepositoryContract
{
    /**
     * PhotoRepository constructor.
     *
     * @param ConnectionInterface $dbConnection
     */
    public function __construct(ConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @inheritdoc
     */
    public function getPhotoById(int $id) : Photo
    {
        $photo = Photo::find($id);

        if (is_null($photo)) {
            throw new ModelNotFoundException('Photo not found.');
        }

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getPublishedPhotoById(int $id) : Photo
    {
        $photo = Photo::whereIsUploaded()->whereIsPublished(true)->find($id);

        if (is_null($photo)) {
            throw new ModelNotFoundException('Photo not found.');
        }

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getUploadedPhotoById(int $id) : Photo
    {
        $photo = Photo::whereIsUploaded()->find($id);

        if (is_null($photo)) {
            throw new ModelNotFoundException('Photo not found.');
        }

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function findPublishedPhotos(int $take, int $skip, $searchQuery = null, $tag = null) : Collection
    {
        $query = Photo::whereIsUploaded()
            ->whereIsPublished(true)
            ->take($take)
            ->skip($skip)
            ->orderBy('created_at', 'desc');

        if (!is_null($searchQuery)) {
            $query = $query->whereSearchQuery($searchQuery);
        }

        if (!is_null($tag)) {
            $query = $query->whereTag($tag);
        }

        $photos = $query->get();

        return $photos;
    }

    /**
     * @inheritdoc
     */
    public function savePublishedPhoto(Photo $photo, array $attributes = [])
    {
        $photo->fill($attributes);

        try {
            $this->dbConnection->beginTransaction();
            $photo->saveOrFail();
            $photo->tags()->delete();
            $photo->tags()->detach();
            $tags = $photo->tags()->createMany($attributes['tags'] ?? []);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw $e;
        }

        $photo->setTagsAttribute($tags);
    }

    /**
     * @inheritdoc
     */
    public function saveUploadedPhoto(Photo $photo, array $attributes = [])
    {
        $photo->fill($attributes);

        try {
            $this->dbConnection->beginTransaction();
            $photo->saveOrFail();
            $photo->exif()->delete();
            $exif = $photo->exif()->create($attributes['exif'] ?? []);
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $thumbnails = $photo->thumbnails()->createMany($attributes['thumbnails'] ?? []);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw $e;
        }

        $photo->setExifAttribute($exif);
        $photo->setThumbnailsAttribute($thumbnails);
    }

    /**
     * @inheritdoc
     */
    public function deletePhoto(Photo $photo) : bool
    {
        return $photo->delete();
    }
}
