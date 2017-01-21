<?php

namespace Api\V1\Http\Resources;

use Exception;
use Throwable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\UploadedPhotoPresenter;

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
     * @return UploadedPhotoPresenter
     */
    public function getById($id) : UploadedPhotoPresenter
    {
        $photo = $this->photo
            ->withThumbnails()
            ->whereIsUploaded()
            ->whereId($id)
            ->first();

        if ($photo === null) {
            throw new ModelNotFoundException('Uploaded photo not found.');
        };

        return new UploadedPhotoPresenter($photo);
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
     * @return UploadedPhotoPresenter
     * @throws Throwable
     */
    public function create(array $attributes) : UploadedPhotoPresenter
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

        return new UploadedPhotoPresenter($photo);
    }

    /**
     * Update a resource.
     *
     * @param UploadedPhotoPresenter $uploadedPhotoPresenter
     * @param array $attributes
     * @return UploadedPhotoPresenter
     * @throws Throwable
     */
    public function update($uploadedPhotoPresenter, array $attributes) : UploadedPhotoPresenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $photo = $uploadedPhotoPresenter->getOriginalModel()->fill($attributes);

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

        return new UploadedPhotoPresenter($photo);
    }

    /**
     * Delete a resource.
     *
     * @param UploadedPhotoPresenter $uploadedPhotoPresenter
     * @return int
     */
    public function delete($uploadedPhotoPresenter) : int
    {
        return (int)$uploadedPhotoPresenter->getOriginalModel()->delete();
    }
}
