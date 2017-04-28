<?php

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
        $this->createTestUser([
            'email' => $email = $this->fake()->safeEmail,
            'password' => $password = $this->fake()->password(),
        ]);

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
        $this->createTestUser([
            'email' => $email = $this->fake()->safeEmail,
            'password' => $password = $this->fake()->password(),
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $this->fake()->safeEmail,
                'password' => $password,
            ])
            ->assertStatus(404);
    }

    public function testCreateInvalidPassword()
    {
        $this->createTestUser([
            'email' => $email = $this->fake()->safeEmail,
            'password' => $password = $this->fake()->password(),
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
                'password' => 'invalid_password',
            ])
            ->assertStatus(404);
    }
}
