<?php

use Api\V1\Http\Resources\UserResource;
use App\Models\DB\Role;
use App\Models\DB\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * Class UserResourceTest
 */
class UserResourceTest extends TestCase
{
    public function testCreateUser()
    {
        $userResource = $this->app->make(UserResource::class);

        $attributes = factory(User::class)->make()->toArray();

        $user = $userResource->create($attributes);

        $this->assertInstanceOf(User::class, $user, 'It should be an instance of User.');
        $this->assertEquals($user->name, $attributes['name'], 'It should be the same names.');
        $this->assertEquals($user->email, $attributes['email'], 'It should be the same emails.');
        $this->assertEquals($user->role->name, Role::customer()->first()->name, 'It should be the same role names.');
    }

    public function testCreateUserWithEmptyAttributes()
    {
        $userResource = $this->app->make(UserResource::class);

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

    public function testCreateUserWithDuplicatedEmailAttribute()
    {
        $userResource = $this->app->make(UserResource::class);

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

    public function testUpdateUser()
    {
        $userResource = $this->app->make(UserResource::class);

        $attributes = factory(User::class)->make()->toArray();

        $user = $userResource->create($attributes);
        $user = $userResource->update($user, ['name' => 'updated_name']);

        $this->assertInstanceOf(User::class, $user, 'It should be an instance of User.');
        $this->assertEquals($user->email, $attributes['email'], 'It should be the same emails.');

        $user = $userResource->update($user, ['email' => 'updated_email@test.test']);

        $this->assertEquals($user->name, 'updated_name', 'It should be the same names.');
        $this->assertEquals($user->email, 'updated_email@test.test', 'It should be the same emails.');
    }

    public function testUpdateUserWithDuplicatedEmailAttribute()
    {
        $userResource = $this->app->make(UserResource::class);

        $firstUserAttributes = factory(User::class)->make()->toArray();
        $secondUserAttributes = factory(User::class)->make()->toArray();

        $firstUser = $userResource->create($firstUserAttributes);
        $secondUser = $userResource->create($secondUserAttributes);

        $this->expectException(ValidationException::class);

        try {
            $userResource->update($secondUser, $firstUserAttributes);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->messages();
            throw $e;
        }

        $this->assertArrayHasKey('email', $errors ?? [], 'It should include an email property error.');
    }

    public function testGetUserById()
    {
        $userResource = $this->app->make(UserResource::class);

        $attributes = factory(User::class)->make()->toArray();

        $userCreated = $userResource->create($attributes);
        $userFound = $userResource->getById($userCreated->id);

        $this->assertInstanceOf(User::class, $userFound, 'It should be an instance of User.');
        $this->assertEquals($userCreated->id, $userFound->id, 'It should be the same ids.');
        $this->assertEquals($userCreated->name, $userFound->name, 'It should be the same names.');
        $this->assertEquals($userCreated->email, $userFound->email, 'It should be the same emails.');
        $this->assertEquals($userCreated->created_at, $userFound->created_at, 'It should be the same dates.');
        $this->assertEquals($userCreated->updated_at, $userFound->updated_at, 'It should be the same dates.');
        $this->assertEquals($userCreated->role, $userFound->role, 'It should be the same roles.');
    }

    public function testDeleteUser()
    {
        $userResource = $this->app->make(UserResource::class);

        $attributes = factory(User::class)->make()->toArray();

        $user = $userResource->create($attributes);

        $count = $userResource->delete($user);

        $this->assertEquals($count, 1, 'It should be equal "1".');
    }

    public function testDeleteNotExistingUser()
    {
        $userResource = $this->app->make(UserResource::class);

        $attributes = factory(User::class)->make()->toArray();

        $user = $userResource->create($attributes);
        $userResource->delete($user);

        $this->expectException(ModelNotFoundException::class);

        $userResource->getById($user->id);
    }
}
