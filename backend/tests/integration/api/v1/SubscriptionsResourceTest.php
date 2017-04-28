<?php

use Core\Models\Subscription;

/**
 * Class SubscriptionsResourceTest.
 */
class SubscriptionsResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'subscriptions';

    protected $resourceStructure = [
        'email',
        'token',
    ];

    public function testCreateSuccess()
    {
        $email = $this->fake()->email;

        $responce = $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $email,
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'email' => $email,
            ]);
    }

    public function testDeleteSuccess()
    {
        $subscription = factory(Subscription::class)->create();

        $responce = $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $subscription->token))
            ->assertStatus(204);
    }
}
