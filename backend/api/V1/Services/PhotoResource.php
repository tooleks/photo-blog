<?php

namespace Api\V1\Services;

use Throwable;
use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class PhotoResource.
 *
 * @property ConnectionInterface db
 * @property Photo $photo
 * @property UploadedPhotoResource uploadedPhotoResource
 * @property string presenterClass
 * @package Api\V1\Services
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
     * @param string $presenterClass
     */
    public function __construct(
        ConnectionInterface $db,
        Photo $photo,
        UploadedPhotoResource $uploadedPhotoResource,
        string $presenterClass
    )
    {
        $this->db = $db;
        $this->photo = $photo;
        $this->uploadedPhotoResource = $uploadedPhotoResource;
        $this->presenterClass = $presenterClass;
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
     * @return Presenter
     */
    public function getById($id) : Presenter
    {
        $photo = $this->photo
            ->withExif()
            ->withThumbnails()
            ->withTags()
            ->whereIsUploaded()
            ->whereIsPublished(true)
            ->whereId($id)
            ->first();

        if (is_null($photo)) {
            throw new ModelNotFoundException('Photo not found.');
        };

        return new $this->presenterClass($photo);
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
            ->withExif()
            ->withTags()
            ->withThumbnails()
            ->whereIsUploaded()
            ->whereIsPublished(true)
            ->take($parameters['take'])
            ->skip($parameters['skip'])
            ->orderByCreatedAt('desc');

        if (array_key_exists('query', $parameters)) {
            $this->photo = $this->photo->whereSearchQuery($parameters['query']);
        }

        if (array_key_exists('tag', $parameters)) {
            $this->photo = $this->photo->whereTag($parameters['tag']);
        }

        $photos = $this->photo->get();

        return $photos->present($this->presenterClass);
    }

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return Presenter
     * @throws Throwable
     */
    public function create(array $attributes) : Presenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $photo = $this->uploadedPhotoResource->getById($attributes['uploaded_photo_id'])->getPresentee();

        $photo->fill($attributes);

        $photo->setIsPublishedAttribute(true);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->tags()->delete();
            $photo->tags()->detach();
            $photo->tags = collect($photo->tags()->createMany($attributes['tags']));
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return new $this->presenterClass($photo);
    }

    /**
     * Update a resource by unique ID.
     *
     * @param int $id
     * @param array $attributes
     * @return Presenter
     * @throws Throwable
     */
    public function updateById($id, array $attributes) : Presenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $photo = $this->getById($id)->getPresentee()->fill($attributes);

        $photo = $photo->fill($attributes);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->tags()->delete();
            $photo->tags()->detach();
            $photo->tags = collect($photo->tags()->createMany($attributes['tags']));
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return new $this->presenterClass($photo);
    }

    /**
     * Delete a resource by unique ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteById($id) : int
    {
        return (int)$this->getById($id)->getPresentee()->delete();
    }
}
