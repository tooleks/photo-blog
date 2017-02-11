<?php

namespace Api\V1\Services;

use Exception;
use Throwable;
use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class UploadedPhotoResource.
 *
 * @property ConnectionInterface db
 * @property Photo $photo
 * @property string presenterClass
 * @package Api\V1\Services
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
     * @param string $presenterClass
     */
    public function __construct(ConnectionInterface $db, Photo $photo, string $presenterClass)
    {
        $this->db = $db;
        $this->photo = $photo;
        $this->presenterClass = $presenterClass;
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
                'exif' => ['required', 'filled', 'array'],
                'thumbnails' => ['required', 'filled', 'array'],
                'thumbnails.*.width' => ['required', 'filled', 'int'],
                'thumbnails.*.height' => ['required', 'filled', 'int'],
                'thumbnails.*.path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'thumbnails.*.relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            ],
            static::VALIDATION_UPDATE => [
                'path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'exif' => ['required', 'filled', 'array'],
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
     * @return Presenter
     */
    public function getById($id) : Presenter
    {
        $photo = $this->photo
            ->withExif()
            ->withThumbnails()
            ->whereIsUploaded()
            ->whereId($id)
            ->first();

        if (is_null($photo)) {
            throw new ModelNotFoundException('Uploaded photo not found.');
        };

        return new $this->presenterClass($photo);
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
     * @return Presenter
     * @throws Throwable
     */
    public function create(array $attributes) : Presenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $photo = $this->photo->newInstance($attributes);

        $photo->setIsPublishedAttribute(false);

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->exif()->create($attributes['exif']);
            $photo->thumbnails = collect($photo->thumbnails()->createMany($attributes['thumbnails']));
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

        try {
            $this->db->beginTransaction();
            $photo->save();
            $photo->exif()->delete();
            $photo->exif()->create($attributes['exif']);
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $photo->thumbnails = collect($photo->thumbnails()->createMany($attributes['thumbnails']));
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
