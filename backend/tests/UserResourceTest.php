<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Api\V1\Http\Resources\UserResource;
use Api\V1\Models\Presenters\UserPresenter;
use App\Models\DB\Role;
use App\Models\DB\User;

/**
 * Class UserResourceTest
 */
class UserResourceTest extends TestCase
{
    /**
     * Create an instance of the UserResource.
     *
     * @return UserResource
     */
    public function createUserResource() : UserResource
    {
        return $this->app->make(UserResource::class);
    }

    public function testCreateUserWithEmptyAttributes()
    {
        $userResource = $this->createUserResource();

        $this->expectException(ValidationException::class);

        try {
            $userResource->create([]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->messages();
            throw $e;
        }

        $this->assertArrayHasKey('name', $errors ?? [], 'It should include a name property error.');
        $this->assertArrayHasKey('email', $errors ?? [], 'It should include an email property error.');
        $this->assertArrayHasKey('password', $errors ?? [], 'It should include a password property error.');
    }

    public function testCreateUserWithDuplicatesEmailAttribute()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $this->expectException(ValidationException::class);

        try {
            $userResource->create($attributes);
            $userResource->create($attributes);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->messages();
            throw $e;
        }

        $this->assertArrayHasKey('email', $errors ?? [], 'It should include an email property error.');
    }

    public function testCreateUser()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $userPresenter = $userResource->create($attributes);

        $this->assertInstanceOf(UserPresenter::class, $userPresenter, 'It should be a instance of UserPresenter.');
        $this->assertEquals($userPresenter->name, $attributes['name'], 'It should be the same names.');
        $this->assertEquals($userPresenter->email, $attributes['email'], 'It should be the same emails.');
        $this->assertEquals($userPresenter->role->name, Role::customer()->first()->name, 'It should be the same role names.');
    }

    public function testUpdateUser()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $userPresenter = $userResource->create($attributes);
        $userPresenter = $userResource->update($userPresenter, ['name' => 'test_updated']);

        $this->assertInstanceOf(UserPresenter::class, $userPresenter, 'It should be a instance of UserPresenter.');
        $this->assertEquals($userPresenter->email, $attributes['email'], 'It should be the same emails.');

        $userPresenter = $userResource->update($userPresenter, ['email' => 'test_updated@test.test']);

        $this->assertEquals($userPresenter->name, 'test_updated', 'It should be the same names.');
        $this->assertEquals($userPresenter->email, 'test_updated@test.test', 'It should be the same emails.');
    }

    public function testGetUserById()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $userPresenterCreated = $userResource->create($attributes);
        $userPresenterFound = $userResource->getById($userPresenterCreated->id);

        $this->assertInstanceOf(UserPresenter::class, $userPresenterFound, 'It should be a instance of UserPresenter.');
        $this->assertEquals($userPresenterCreated->id, $userPresenterFound->id, 'It should be the same ids.');
        $this->assertEquals($userPresenterCreated->name, $userPresenterFound->name, 'It should be the same names.');
        $this->assertEquals($userPresenterCreated->email, $userPresenterFound->email, 'It should be the same emails.');
        $this->assertEquals($userPresenterCreated->created_at, $userPresenterFound->created_at, 'It should be the same dates.');
        $this->assertEquals($userPresenterCreated->updated_at, $userPresenterFound->updated_at, 'It should be the same dates.');
        $this->assertEquals($userPresenterCreated->role, $userPresenterFound->role, 'It should be the same roles.');
    }

    public function testDeleteUser()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $userPresenter = $userResource->create($attributes);

        $count = $userResource->delete($userPresenter);

        $this->assertEquals($count, 1, 'It should be equal "1".');
    }

    public function testDeleteNotExistingUser()
    {
        $userResource = $this->createUserResource();

        $attributes = factory(User::class)->make()->toArray();

        $userPresenter = $userResource->create($attributes);
        $userResource->delete($userPresenter);

        $this->expectException(ModelNotFoundException::class);

        $userResource->getById($userPresenter->id);
    }
}
