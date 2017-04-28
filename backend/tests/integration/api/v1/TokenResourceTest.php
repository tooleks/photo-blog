<?php

use Core\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class TokenResourceTest.
 */
class TokenResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'token';

    protected $resourceStructure = [
        'user_id',
        'api_token',
    ];

    public function testCreateSuccess()
    {
        $user = new User;
        $user->name = $this->fake()->userName;
        $user->email = $email = $this->fake()->safeEmail;
        $user->password = Hash::make($password = $this->fake()->password());
        $user->generateApiToken();
        $user->setAdministratorRole();
        $user->saveOrFail();

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
                'password' => $password,
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure);
    }

    public function testCreateInvalidEmail()
    {
        $user = new User;
        $user->name = $this->fake()->userName;
        $user->email = $email = $this->fake()->safeEmail;
        $user->password = Hash::make($password = $this->fake()->password());
        $user->generateApiToken();
        $user->setAdministratorRole();
        $user->saveOrFail();

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $this->fake()->safeEmail,
                'password' => $password,
            ])
            ->assertStatus(404);
    }

    public function testCreateInvalidPassword()
    {
        $user = new User;
        $user->name = $this->fake()->userName;
        $user->email = $email = $this->fake()->safeEmail;
        $user->password = Hash::make($password = $this->fake()->password());
        $user->generateApiToken();
        $user->setAdministratorRole();
        $user->saveOrFail();

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
                'password' => 'invalid_password',
            ])
            ->assertStatus(404);
    }
}
