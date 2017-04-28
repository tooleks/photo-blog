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

    protected function createTestSubscription()
    {
        $subscription = factory(Subscription::class)->create();

        return $subscription;
    }

    public function testCreateSuccess()
    {
        $email = $this->fake()->email;

        $this
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
        $subscription = $this->createTestSubscription();

        $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $subscription->token))
            ->assertStatus(204);

        $this->assertFalse(Subscription::whereToken($subscription->token)->exists());
    }
}
