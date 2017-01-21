<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Throwable;

/**
 * Class UploadedPhotoResource
 *
 * @property ConnectionInterface db
 * @property Photo $photo
 * @package Api\V1\Http\Resources
 */
class UploadedPhotoResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'validation.create';
    const VALIDATION_UPDATE = 'validation.update';

    /**
     * UploadedPhotoResource constructor.
     *
     * @param ConnectionInterface $db
     * @param Photo $photo
     */
    public function __construct(ConnectionInterface $db, Photo $photo)
    {
        $this->db = $db;
        $this->photo = $photo;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'user_id' => ['required', 'filled', 'integer'],
                'path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'thumbnails' => ['required', 'filled', 'array'],
                'thumbnails.*.width' => ['required', 'filled', 'int'],
                'thumbnails.*.height' => ['required', 'filled', 'int'],
                'thumbnails.*.path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'thumbnails.*.relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            ],
            static::VALIDATION_UPDATE => [
                'path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'thumbnails' => ['required', 'filled', 'array'],
                'thumbnails.*.width' => ['required', 'filled', 'int'],
                'thumbnails.*.height' => ['required', 'filled', 'int'],
                'thumbnails.*.path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'thumbnails.*.relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
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
            ->whereIsUploaded()
            ->whereId($id)
            ->first();

        if ($photo === null) {
            throw new ModelNotFoundException('Uploaded photo not found.');
        };

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function getCollection($take, $skip, array $parameters)
    {
        throw new Exception('Method not implemented.');
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

        $photo = $this->photo->newInstance(['is_published' => false] + $attributes);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $photo->thumbnails()->createMany($attributes['thumbnails']);
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
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $photo->thumbnails = $photo->thumbnails()->createMany($attributes['thumbnails']);
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
     */
    public function delete($photo) : int
    {
        return (int)$photo->delete();
    }
}
