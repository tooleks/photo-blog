<?php

namespace Api\V1\Services;

use Exception;
use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class UserResource.
 *
 * @property User user
 * @property Hasher hasher
 * @property string presenterClass
 * @package Api\V1\Services
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
     * @param string $presenterClass
     */
    public function __construct(User $user, Hasher $hasher, string $presenterClass)
    {
        $this->user = $user;
        $this->hasher = $hasher;
        $this->presenterClass = $presenterClass;
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
     * @return Presenter
     */
    public function getById($id) : Presenter
    {
        $user = $this->user->whereId($id)->first();

        if (is_null($user)) {
            throw new ModelNotFoundException('User not found.');
        };

        return new $this->presenterClass($user);
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
     */
    public function create(array $attributes) : Presenter
    {
        $attributes = $this->validate($attributes, static::VALIDATION_CREATE);

        $user = $this->user->newInstance($attributes);

        $user->setPasswordHash($this->hasher->make($attributes['password']))
            ->generateApiToken()
            ->setCustomerRole();

        $user->saveOrFail();

        return new $this->presenterClass($user);
    }

    /**
     * Update a resource by unique ID.
     *
     * @param int $id
     * @param array $attributes
     * @return Presenter
     */
    public function updateById($id, array $attributes) : Presenter
    {
        $this->validationAttributes['email'] = $user->email;

        $attributes = $this->validate($attributes, static::VALIDATION_UPDATE);

        $user = $this->getById($id)->getPresentee()->fill($attributes);

        if (array_key_exists('password', $attributes)) {
            $user->setPasswordHash($this->hasher->make($attributes['password']));
        }

        $user->saveOrFail();

        return new $this->presenterClass($user);
    }

    /**
     * Delete a resource by unique ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteById($id) : int
    {
        $user = $this->getById($id)->getPresentee();

        return (int)$user->delete();
    }
}
