<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Exception;

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
     * @return User
     */
    public function getById($id) : User
    {
        $user = $this->user
            ->whereId($id)
            ->first();

        if ($user === null) {
            throw new ModelNotFoundException('User not found.');
        };

        return $user;
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
     * @return User
     */
    public function create(array $attributes) : User
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $user = $this->user->newInstance($attributes);

        $user->setPasswordHash($this->hasher->make($attributes['password']))
            ->generateApiToken()
            ->setCustomerRole();

        $user->saveOrFail();

        return $user;
    }

    /**
     * Update a resource.
     *
     * @param User $user
     * @param array $attributes
     * @return User
     */
    public function update($user, array $attributes) : User
    {
        $this->validationAttributes['email'] = $user->email;

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $user = $user->fill($attributes);

        if (isset($attributes['password'])) {
            $user->setPasswordHash($this->hasher->make($attributes['password']));
        }

        $user->saveOrFail();

        return $user;
    }

    /**
     * Delete a resource.
     *
     * @param User $user
     * @return int
     */
    public function delete($user) : int
    {
        return (int)$user->delete();
    }
}
