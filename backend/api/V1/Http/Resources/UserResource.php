<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\UserPresenter;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Exception;

/**
 * Class UserResource
 * @property User userModel
 * @property Hasher hasher
 * @package Api\V1\Http\Resources
 */
class UserResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'create';
    const VALIDATION_UPDATE = 'update';

    /**
     * @var array
     */
    protected $validationAttributes = [];

    /**
     * UserResource constructor.
     * @param User $userModel
     * @param Hasher $hasher
     */
    public function __construct(User $userModel, Hasher $hasher)
    {
        $this->userModel = $userModel;
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'name' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'filled',
                    'string',
                    'email',
                    'unique:users',
                    'min:1',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
            ],
            static::VALIDATION_UPDATE => [
                'name' => [
                    'filled',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'email' => [
                    'filled',
                    'string',
                    'email',
                    Rule::unique('users')->ignore($this->validationAttributes['email'] ?? '', 'email'),
                    'min:1',
                    'max:255',
                ],
                'password' => [
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
     * @return UserPresenter
     */
    public function getById($id) : UserPresenter
    {
        $user = $this->userModel
            ->whereId($id)
            ->first();

        if ($user === null) {
            throw new ModelNotFoundException('User not found.');
        };

        return new UserPresenter($user);
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
     * @return UserPresenter
     */
    public function create(array $attributes) : UserPresenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $user = $this->userModel->newInstance();

        $user->setPasswordHash($this->hasher->make($attributes['password']));
        $user->generateApiToken();
        $user->setCustomerRole();

        $user->saveWithRelationsOrFail($attributes);

        return $this->getById($user->id);
    }

    /**
     * Update a resource.
     *
     * @param UserPresenter $userPresenter
     * @param array $attributes
     * @return mixed
     */
    public function update($userPresenter, array $attributes) : UserPresenter
    {
        /** @var User $user */

        $this->validationAttributes['email'] = $userPresenter->email;

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $user = $userPresenter->getOriginalEntity();

        if (isset($attributes['password'])) {
            $user->setPasswordHash($this->hasher->make($attributes['password']));
        }

        $user->saveOrFail();

        return $this->getById($user->id);
    }

    /**
     * Delete a resource
     *
     * @param UserPresenter $userPresenter
     * @return int
     */
    public function delete($userPresenter) : int
    {
        /** @var User $photo */

        $user = $userPresenter->getOriginalEntity();

        $result = $user->deleteWithRelationsOrFail();

        return (int)$result;
    }
}
