<?php

namespace Api\V1\Http\Resources;

use Api\V1\Models\Presenters\PhotoCollectionPresenter;
use App\Core\Validator\Validator;
use App\Models\DB\Photo;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\PhotoPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

/**
 * Class PhotoResource
 * @property Photo $photoModel
 * @property UploadedPhotoResource uploadedPhotoResource
 * @package Api\V1\Http\Resources
 */
class PhotoResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'create';
    const VALIDATION_UPDATE = 'update';
    const VALIDATION_GET_LIST = 'get-list';

    /**
     * PhotoResource constructor.
     * @param Photo $photoModel
     * @param UploadedPhotoResource $uploadedPhotoResource
     */
    public function __construct(Photo $photoModel, UploadedPhotoResource $uploadedPhotoResource)
    {
        $this->photoModel = $photoModel;
        $this->uploadedPhotoResource = $uploadedPhotoResource;
    }

    /**
     * @inheritdoc
     */
    public function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'uploaded_photo_id' => [
                    'required',
                    'filled',
                    'integer',
                ],
                'description' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:65535',
                ],
                'tags' => [
                    'required',
                    'filled',
                    'array',
                ],
                'tags.*.text' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
            ],
            static::VALIDATION_UPDATE => [
                'description' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:65535',
                ],
                'tags' => [
                    'required',
                    'filled',
                    'array',
                ],
                'tags.*.text' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
            ],
            static::VALIDATION_GET_LIST => [
                'take' => [
                    'required',
                    'filled',
                    'integer',
                    'min:1',
                ],
                'skip' => [
                    'required',
                    'filled',
                    'integer',
                    'min:0',
                ],
                'query' => [
                    'filled',
                    'string',
                    'min:1',
                ],
                'tag' => [
                    'filled',
                    'string',
                    'min:1',
                ],
            ],
        ];
    }

    /**
     * Get a resource by unique ID.
     *
     * @param int $id
     * @return PhotoPresenter
     */
    public function getById($id) : PhotoPresenter
    {
        $photo = $this->photoModel
            ->withThumbnails()
            ->withTags()
            ->whereUploaded()
            ->wherePublished(true)
            ->whereId($id)
            ->first();

        if ($photo === null) {
            throw new ModelNotFoundException('Record not found.');
        };

        return new PhotoPresenter($photo);
    }

    /**
     * Get resources list by parameters.
     *
     * @param int $take
     * @param int $skip
     * @param array $parameters
     * @return PhotoCollectionPresenter
     */
    public function getList($take, $skip, array $parameters) : PhotoCollectionPresenter
    {
        $parameters = $this->validate(['take' => $take, 'skip' => $skip] + $parameters, static::VALIDATION_GET_LIST);

        $this->photoModel = $this->photoModel
            ->withThumbnails()
            ->withTags()
            ->whereUploaded()
            ->wherePublished(true)
            ->take($parameters['take'])
            ->skip($parameters['skip'])
            ->orderByCreatedAt('desc');

        if (isset($parameters['query'])) {
            $this->photoModel = $this->photoModel->whereSearchQuery($parameters['query']);
        } elseif (isset($parameters['tag'])) {
            $this->photoModel = $this->photoModel->whereTag($parameters['tag']);
        }

        $photos = $this->photoModel->get();

        return new PhotoCollectionPresenter($photos);
    }

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return PhotoPresenter
     * @throws Throwable
     */
    public function create(array $attributes) : PhotoPresenter
    {
        /** @var Photo $photo */

        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $photo = $this->uploadedPhotoResource->getById($attributes['uploaded_photo_id'])->getOriginalEntity();

        $photo->saveWithRelationsOrFail(['is_draft' => false] + $attributes, ['tags'], true);

        return $this->getById($photo->id);
    }

    /**
     * Update a resource.
     *
     * @param PhotoPresenter $photoPresenter
     * @param array $attributes
     * @return PhotoPresenter
     */
    public function update($photoPresenter, array $attributes) : PhotoPresenter
    {
        /** @var Photo $photo */

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $photo = $photoPresenter->getOriginalEntity();

        $photo->saveWithRelationsOrFail($attributes, ['tags'], true);

        return $this->getById($photo->id);
    }

    /**
     * Delete a resource
     *
     * @param PhotoPresenter $photoPresenter
     * @return int
     * @throws Throwable
     */
    public function delete($photoPresenter) : int
    {
        /** @var Photo $photo */

        $photo = $photoPresenter->getOriginalEntity();

        $result = $photo->deleteWithRelationsOrFail();

        return (int)$result;
    }
}
