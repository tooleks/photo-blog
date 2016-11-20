<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\UploadedPhotoPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Class UploadedPhoto
 * @property Photo $photoModel
 * @package Api\V1\Http\Resources
 */
class UploadedPhotoResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'create';
    const VALIDATION_UPDATE = 'update';

    /**
     * UploadedPhoto constructor.
     *
     * @param Photo $photoModel
     */
    public function __construct(Photo $photoModel)
    {
        $this->photoModel = $photoModel;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'user_id' => [
                    'required',
                    'filled',
                    'integer',
                ],
                'path' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'relative_url' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'thumbnails' => [
                    'required',
                    'filled',
                    'array',
                ],
                'thumbnails.*.width' => [
                    'required',
                    'filled',
                    'int',
                ],
                'thumbnails.*.height' => [
                    'required',
                    'filled',
                    'int',
                ],
                'thumbnails.*.path' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'thumbnails.*.relative_url' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
            ],
            static::VALIDATION_UPDATE => [
                'path' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'relative_url' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'thumbnails' => [
                    'required',
                    'filled',
                    'array',
                ],
                'thumbnails.*.width' => [
                    'required',
                    'filled',
                    'int',
                ],
                'thumbnails.*.height' => [
                    'required',
                    'filled',
                    'int',
                ],
                'thumbnails.*.path' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'thumbnails.*.relative_url' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
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
        $photo = $this->photoModel
            ->withThumbnails()
            ->whereUploaded()
            ->whereId($id)
            ->first();

        if ($photo === null) {
            throw new ModelNotFoundException('Record not found.');
        };

        return new UploadedPhotoPresenter($photo);
    }

    /**
     * @inheritdoc
     */
    public function getList($take, $skip, array $parameters)
    {
        throw new Exception('Method not implemented.');
    }

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return UploadedPhotoPresenter
     */
    public function create(array $attributes) : UploadedPhotoPresenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $photo = $this->photoModel->create();

        $photo->saveWithRelationsOrFail(['is_draft' => true] + $attributes, ['thumbnails'], true);

        return $this->getById($photo->id);
    }

    /**
     * Update a resource.
     *
     * @param UploadedPhotoPresenter $uploadedPhotoPresenter
     * @param array $attributes
     * @return UploadedPhotoPresenter
     */
    public function update($uploadedPhotoPresenter, array $attributes) : UploadedPhotoPresenter
    {
        /** @var Photo $photo */

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $photo = $uploadedPhotoPresenter->getOriginalEntity();

        $photo->saveWithRelationsOrFail($attributes, ['thumbnails'], true);

        return $this->getById($photo->id);
    }

    /**
     * Delete a resource.
     *
     * @param UploadedPhotoPresenter $uploadedPhotoPresenter
     * @return int
     */
    public function delete($uploadedPhotoPresenter) : int
    {
        /** @var Photo $photo */

        $photo = $uploadedPhotoPresenter->getOriginalEntity();

        $result = $photo->deleteWithRelationsOrFail();

        return (int)$result;
    }
}
