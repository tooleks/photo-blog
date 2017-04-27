<?php

/***
 * Class SubscriptionsTest.
 */
class SubscriptionsTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'subscriptions';

    public function testPostValidation()
    {
        $this
            ->json('POST', sprintf('/%s', $this->resourceName))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => 'invalid_email',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testPost()
    {
        $email = $this->fake()->email;

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
            ])
            ->assertStatus(201)
            ->assertJsonStructure(['email', 'token'])
            ->assertJson([
                'email' => $email,
            ]);
    }

    public function testPostDuplicatedEmail()
    {
        $email = $this->fake()->email;

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
            ])
            ->assertStatus(201);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testDelete()
    {
        $createdSubscription = $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $this->fake()->email,
            ])
            ->assertStatus(201)
            ->getData(true);

        $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $createdSubscription['token'] ?? null))
            ->assertStatus(204);
    }
}
