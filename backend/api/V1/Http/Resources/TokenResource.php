<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Class Token.
 *
 * @property User user
 * @property Hasher hasher
 * @package Api\V1\Http\Resourcess
 */
class TokenResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'validation.create';

    /**
     * TokenResource constructor.
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
                'email' => ['required', 'filled', 'email', 'min:1', 'max:255'],
                'password' => ['required', 'filled', 'min:1', 'max:255'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        throw new Exception('Method not implemented.');
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
        $this->validate($attributes, static::VALIDATION_CREATE);

        $user = $this->user->whereEmail($attributes['email'])->first();

        if ($user === null) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!$this->hasher->check($attributes['password'], $user->password)) {
            throw new ModelNotFoundException('Invalid user password.');
        }

        $user->generateApiToken()->saveOrFail();

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function update($resource, array $attributes)
    {
        throw new Exception('Method not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function delete($resource)
    {
        throw new Exception('Method not implemented.');
    }
}
