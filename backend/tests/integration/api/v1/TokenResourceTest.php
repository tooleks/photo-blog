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
        $user = $this->createTestUser([
            'email' => $email = $this->fake()->safeEmail,
            'password' => $password = $this->fake()->password(),
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
                'password' => $password,
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'user_id' => $user->id
            ]);
    }

    public function testCreateWithInvalidEmail()
    {
        $user = $this->createTestUser([
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

    public function testCreateWithInvalidPassword()
    {
        $user = $this->createTestUser([
            'email' => $email = $this->fake()->safeEmail,
            'password' => $password = $this->fake()->password(),
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
                'password' => $this->fake()->password(),
            ])
            ->assertStatus(404);
    }
}
