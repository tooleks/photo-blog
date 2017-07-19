<?php

use App\Models\User;

/**
 * Class UsersResourceTest.
 */
class UsersResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'users';

    protected $resourceStructure = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at',
        'role',
    ];

    public function testCreateSuccess()
    {
        $authUser = $this->createTestUser();

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName($this->resourceName), $body = [
                'name' => $this->fake()->userName,
                'email' => $this->fake()->safeEmail,
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'name' => $body['name'],
                'email' => $body['email'],
            ]);
    }

    public function testCreateWithDuplicatedEmail()
    {
        $authUser = $this->createTestUser($data = []);

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName($this->resourceName), [
                'name' => $this->fake()->userName,
                'email' => $authUser->email,
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testCreateUnauthorized()
    {
        $this
            ->json('POST', $this->getResourceFullName($this->resourceName), [
                'name' => $this->fake()->userName,
                'email' => $this->fake()->safeEmail,
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(401);
    }

    public function testUpdateSuccess()
    {
        $authUser = $this->createTestUser();
        $user = $this->createTestUser();

        $this
            ->actingAs($authUser)
            ->json('PUT', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)), $body = [
                'name' => $this->fake()->userName,
                'email' => $this->fake()->safeEmail,
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'name' => $body['name'],
                'email' => $body['email'],
            ]);
    }

    public function testUpdateWithDuplicatedEmail()
    {
        $authUser = $this->createTestUser();
        $user = $this->createTestUser();
        $userWithEmail = $this->createTestUser($data = ['email' => $this->fake()->safeEmail]);

        $this
            ->actingAs($authUser)
            ->json('PUT', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)), $body = [
                'name' => $this->fake()->userName,
                'email' => $data['email'],
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testUpdateUnauthorized()
    {
        $user = $this->createTestUser();

        $this
            ->json('PUT', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)))
            ->assertStatus(401);
    }

    public function testGetByIdSuccess()
    {
        $authUser = $this->createTestUser();
        $user = $this->createTestUser();

        $this
            ->actingAs($authUser)
            ->json('GET', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function testGetByIdUnauthorized()
    {
        $user = $this->createTestUser();

        $this
            ->json('GET', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)))
            ->assertStatus(401);
    }

    public function testDeleteSuccess()
    {
        $authUser = $this->createTestUser();
        $user = $this->createTestUser();

        $this
            ->actingAs($authUser)
            ->json('DELETE', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)))
            ->assertStatus(204);

        $this->assertFalse(User::whereId($user->id)->exists(), 'The user was not deleted.');
    }

    public function testDeleteUnauthorized()
    {
        $user = $this->createTestUser();

        $this
            ->json('DELETE', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $user->id)))
            ->assertStatus(401);
    }
}
