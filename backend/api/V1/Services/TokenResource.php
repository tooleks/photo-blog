<?php

namespace Api\V1\Services;

use Exception;
use App\Core\Validator\Validator;
use App\Models\DB\User;
use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TokenResource.
 *
 * @property User user
 * @property Hasher hasher
 * @property string presenterClass
 * @package Api\V1\Services
 */
class TokenResource implements Resource
{
    use Validator;

    /**
     * TokenResource constructor.
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
            'create' => [
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
    public function get($take, $skip, array $parameters)
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
        $this->validate($attributes, __FUNCTION__);

        $user = $this->user->whereEmail($attributes['email'])->first();

        if (is_null($user)) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!$this->hasher->check($attributes['password'], $user->password)) {
            throw new ModelNotFoundException('Invalid user password.');
        }

        $user->generateApiToken();

        $user->saveOrFail();

        return new $this->presenterClass($user);
    }

    /**
     * @inheritdoc
     */
    public function updateById($id, array $attributes)
    {
        throw new Exception('Method not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        throw new Exception('Method not implemented.');
    }
}
