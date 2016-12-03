<?php

namespace Api\V1\Http\Resources;

use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Models\Presenters\TokenPresenter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\CountValidator\Exception;

/**
 * Class Token
 *
 * The class provides CRUD for user tokens.
 *
 * @property User userModel
 * @property Guard guard
 * @property Hasher hasher
 * @package Api\V1\Http\Resourcess
 */
class TokenResource implements Resource
{
    use Validator;

    const VALIDATION_CREATE = 'create';

    /**
     * Token constructor.
     *
     * @param User $userModel
     * @param Guard $guard
     * @param Hasher $hasher
     */
    public function __construct(User $userModel, Guard $guard, Hasher $hasher)
    {
        $this->userModel = $userModel;
        $this->guard = $guard;
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    protected function getValidationRules() : array
    {
        return [
            static::VALIDATION_CREATE => [
                'email' => [
                    'required',
                    'filled',
                    'email',
                    'min:1',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'filled',
                    'min:1',
                    'max:255',
                ],
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
     * @return TokenPresenter
     */
    public function create(array $attributes) : TokenPresenter
    {
        /** @var User $user */

        $this->validate($attributes, static::VALIDATION_CREATE);

        $user = $this->userModel->whereEmail($attributes['email'])->first();

        if ($user === null) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!$this->hasher->check($attributes['password'], $user->password)) {
            throw new ModelNotFoundException('Invalid user password.');
        }

        $user->generateApiToken();
        $user->saveOrFail();

        $this->guard->setUser($user);

        return new TokenPresenter($user);
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
