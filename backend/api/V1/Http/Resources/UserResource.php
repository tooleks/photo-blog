<?php

namespace Api\V1\Http\Resources;

use Exception;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\UserPresenter;

/**
 * Class UserResource
 *
 * @property User user
 * @property Hasher hasher
 * @package Api\V1\Http\Resources
 */
class UserResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'validation.create';
    const VALIDATION_UPDATE = 'validation.update';

    /**
     * @var array
     */
    protected $validationAttributes = [];

    /**
     * UserResource constructor.
     *
     * @param User $user
     * @param Hasher $hasher
     */
    public function __construct(User $user, Hasher $hasher)
    {
        $this->user = $user;
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'name' => ['required', 'filled', 'string', 'min:1', 'max:255'],
                'email' => ['required', 'filled', 'string', 'email', 'unique:users', 'min:1', 'max:255'],
                'password' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            ],
            static::VALIDATION_UPDATE => [
                'name' => ['filled', 'string', 'min:1', 'max:255'],
                'email' => [
                    'filled',
                    'string',
                    'email',
                    Rule::unique('users')->ignore($this->validationAttributes['email'] ?? '', 'email'),
                    'min:1',
                    'max:255',
                ],
                'password' => ['filled', 'string', 'min:1', 'max:255'],
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
        $user = $this->user
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
    public function getCollection($take, $skip, array $parameters)
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

        $user = $this->user->newInstance($attributes);

        $user->setPasswordHash($this->hasher->make($attributes['password']))
            ->generateApiToken()
            ->setCustomerRole();

        $user->saveOrFail();

        return new UserPresenter($user);
    }

    /**
     * Update a resource.
     *
     * @param UserPresenter $userPresenter
     * @param array $attributes
     * @return UserPresenter
     */
    public function update($userPresenter, array $attributes) : UserPresenter
    {
        $this->validationAttributes['email'] = $userPresenter->email;

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $user = $userPresenter->getOriginalModel()->fill($attributes);

        if (isset($attributes['password'])) {
            $user->setPasswordHash($this->hasher->make($attributes['password']));
        }

        $user->saveOrFail();

        return new UserPresenter($user);
    }

    /**
     * Delete a resource.
     *
     * @param UserPresenter $userPresenter
     * @return int
     */
    public function delete($userPresenter) : int
    {
        return (int)$userPresenter->getOriginalModel()->delete();
    }
}
