<?php

namespace App\Services\Photos;

use Exception;
use App\Models\Photo;
use App\Services\Photos\Contracts\PhotoServiceContract;
use App\Services\Photos\Events\PhotoAfterDelete;
use App\Services\Photos\Events\PhotoAfterFind;
use App\Services\Photos\Events\PhotoAfterSave;
use App\Services\Photos\Events\PhotoAfterValidate;
use App\Services\Photos\Events\PhotoBeforeDelete;
use App\Services\Photos\Events\PhotoBeforeFind;
use App\Services\Photos\Events\PhotoBeforeSave;
use App\Services\Photos\Events\PhotoBeforeValidate;
use App\Services\Photos\Exceptions\PhotoException;
use App\Services\Photos\Exceptions\PhotoNotFoundException;
use App\Services\Photos\Exceptions\PhotoValidationException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class PhotoService.
 * @property ConnectionInterface connection
 * @property ValidatorFactory validator
 * @property string scenario
 * @package App\Services\Photos
 */
class PhotoService implements PhotoServiceContract
{
    /**
     * PhotoService constructor.
     *
     * @param ConnectionInterface $connection
     * @param ValidatorFactory $validator
     */
    public function __construct(ConnectionInterface $connection, ValidatorFactory $validator)
    {
        $this->connection = $connection;
        $this->validator = $validator;

        $this->setScenario('default');
    }

    /**
     * @inheritdoc
     */
    public function setScenario(string $scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    private function getValidationRules() : array
    {
        return [
            'description' => [
                'required',
                'filled',
                'string',
                'min:1',
                'max:65535',
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
            'is_draft' => [
                'boolean',
            ],
        ];
    }

    /**
     * Validate attributes.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return Validator
     * @throws PhotoValidationException
     */
    private function validate(Photo &$photo, array &$attributes) : Validator
    {
        $validator = $this->validator->make($attributes, $this->getValidationRules());

        event(new PhotoBeforeValidate($photo, $validator, $attributes, $this->scenario));
        if ($validator->fails()) {
            throw new PhotoValidationException($validator);
        }
        event(new PhotoAfterValidate($photo, $validator, $attributes, $this->scenario));

        return $validator;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes, bool $validate = true) : Photo
    {
        $photo = $this->save(new Photo, $attributes, $validate);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function save(Photo $photo, array $attributes, bool $validate = true) : Photo
    {
        if ($validate) {
            $this->validate($photo, $attributes);
        }

        $this->connection->beginTransaction();
        try {
            event(new PhotoBeforeSave($photo, $attributes, $this->scenario));
            $photo->fill($attributes)->save();
            event(new PhotoAfterSave($photo, $attributes, $this->scenario));
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw new PhotoException('Photo was not saved.');
        }

        $photo = $this->getById($photo->id);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function delete(Photo $photo) : int
    {
        $this->connection->beginTransaction();
        try {
            event(new PhotoBeforeDelete($photo));
            $count = $photo->delete();
            event(new PhotoAfterDelete($photo, $count));
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw new PhotoException('Photo was not deleted.');
        }

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id) : Photo
    {
        $photo = new Photo;

        try {
            event(new PhotoBeforeFind($photo));
            $photo = $photo->findOrFail($id);
            event(new PhotoAfterFind($photo));
        } catch (Exception $e) {
            throw new PhotoNotFoundException('Photo was not found.');
        }

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function get(int $take = 10, int $skip = 0) : Collection
    {
        $photo = new Photo;

        $parameters = [];

        try {
            event(new PhotoBeforeFind($photo, $parameters, $this->scenario));
            $photos = $photo
                ->isDraft(false)
                ->orderBy('created_at', 'desc')
                ->take($take)
                ->skip($skip)
                ->get();
            event(new PhotoAfterFind($photos));
        } catch (Exception $e) {
            throw new PhotoNotFoundException('Photos was not found.');
        }

        return $photos;
    }

    /**
     * @inheritdoc
     */
    public function getBySearchParameters(int $take = 10, int $skip = 0, array $parameters) : Collection
    {
        $photo = new Photo;

        try {
            event(new PhotoBeforeFind($photo, $parameters, $this->scenario));
            $photos = $photo
                ->isDraft(false)
                ->where('description', 'like', "%{$parameters['query']}%")
                ->orderBy('created_at', 'desc')
                ->take($take)
                ->skip($skip)
                ->get();
            event(new PhotoAfterFind($photos));
        } catch (Exception $e) {
            throw new PhotoNotFoundException('Photos was not found.');
        }

        return $photos;
    }
}
