<?php

use App\Models\Subscription;

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

    protected function createTestSubscription(array $attributes = [])
    {
        $subscription = factory(Subscription::class)->create($attributes);

        return $subscription;
    }

    public function testCreateSuccess()
    {
        $this
            ->json('POST', sprintf('/%s', $this->resourceName), $body = [
                'email' => $this->fake()->email,
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'email' => $body['email'],
            ]);
    }

    public function testCreateWithDuplicatedEmail()
    {
        $subscription = $this->createTestSubscription($data = [
            'email' => $this->fake()->email,
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $data['email'],
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testDeleteSuccess()
    {
        $subscription = $this->createTestSubscription();

        $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $subscription->token))
            ->assertStatus(204);

        $this->assertFalse(Subscription::whereToken($subscription->token)->exists(), 'The subscription was not deleted.');
    }
}
