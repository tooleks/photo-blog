<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class PhotoResource
 *
 * The class provides CRUD for photos that are uploaded and published.
 *
 * @property ConnectionInterface db
 * @property Photo $photo
 * @property UploadedPhotoResource uploadedPhotoResource
 * @package Api\V1\Http\Resources
 */
class PhotoResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'validation.create';
    const VALIDATION_UPDATE = 'validation.update';
    const VALIDATION_GET_COLLECTION = 'validation.get.collection';

    /**
     * PhotoResource constructor.
     *
     * @param ConnectionInterface $db
     * @param Photo $photo
     * @param UploadedPhotoResource $uploadedPhotoResource
     */
    public function __construct(ConnectionInterface $db, Photo $photo, UploadedPhotoResource $uploadedPhotoResource)
    {
        $this->db = $db;
        $this->photo = $photo;
        $this->uploadedPhotoResource = $uploadedPhotoResource;
    }

    /**
     * @inheritdoc
     */
    public function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'uploaded_photo_id' => ['required', 'filled', 'integer'],
                'description' => ['required', 'filled', 'string', 'min:1', 'max:65535'],
                'tags' => ['required', 'filled', 'array'],
                'tags.*.text' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            ],
            static::VALIDATION_UPDATE => [
                'description' => ['required', 'filled', 'string', 'min:1', 'max:65535'],
                'tags' => ['required', 'filled', 'array'],
                'tags.*.text' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            ],
            static::VALIDATION_GET_COLLECTION => [
                'take' => ['required', 'filled', 'integer', 'min:1', 'max:100'],
                'skip' => ['required', 'filled', 'integer', 'min:0'],
                'query' => ['filled', 'string', 'min:1'],
                'tag' => ['filled', 'string', 'min:1'],
            ],
        ];
    }

    /**
     * Get a resource by unique ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getById($id) : Photo
    {
        $photo = $this->photo
            ->withThumbnails()
            ->withTags()
            ->whereIsUploaded()
            ->whereIsPublished(true)
            ->whereId($id)
            ->first();

        if ($photo === null) {
            throw new ModelNotFoundException('Photo not found.');
        };

        return $photo;
    }

    /**
     * Get resources collection by parameters.
     *
     * @param int $take
     * @param int $skip
     * @param array $parameters
     * @return Collection
     */
    public function getCollection($take, $skip, array $parameters) : Collection
    {
        $parameters = $this->validate(['take' => $take, 'skip' => $skip] + $parameters, static::VALIDATION_GET_COLLECTION);

        $this->photo = $this->photo
            ->distinct()
            ->withThumbnails()
            ->withTags()
            ->whereIsUploaded()
            ->whereIsPublished(true)
            ->take($parameters['take'])
            ->skip($parameters['skip'])
            ->orderByCreatedAt('desc');

        if (isset($parameters['query'])) {
            $this->photo = $this->photo->whereSearchQuery($parameters['query']);
        } elseif (isset($parameters['tag'])) {
            $this->photo = $this->photo->whereTag($parameters['tag']);
        }

        $photos = $this->photo->get();

        return $photos;
    }

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return Photo
     * @throws Throwable
     */
    public function create(array $attributes) : Photo
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $photo = $this->uploadedPhotoResource->getById($attributes['uploaded_photo_id']);

        $photo->fill(['is_published' => true] + $attributes);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->tags()->delete();
            $photo->tags()->detach();
            $photo->tags()->createMany($attributes['tags']);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $photo;
    }

    /**
     * Update a resource.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return Photo
     * @throws Throwable
     */
    public function update($photo, array $attributes) : Photo
    {
        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $photo = $photo->fill($attributes);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->tags()->delete();
            $photo->tags()->detach();
            $photo->tags = $photo->tags()->createMany($attributes['tags']);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $photo;
    }

    /**
     * Delete a resource.
     *
     * @param Photo $photo
     * @return int
     * @throws Throwable
     */
    public function delete($photo) : int
    {
        return (int)$photo->delete();
    }
}
