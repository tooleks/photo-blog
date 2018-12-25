<?php

namespace Tests\Integration\Api\V1;

use App\Models\Subscription;

/**
 * Class SubscriptionsResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class SubscriptionsResourceTest extends TestCase
{
    public function validCreateAttributesProvider(): array
    {
        return [
            [['email' => 'john.doe@website.domain']],
        ];
    }

    public function invalidCreateAttributesProvider(): array
    {
        return [
            [['email' => 'john.doe_website.domain']],
            [[]],
        ];
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateSuccess(array $requestBody): void
    {
        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(201)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJson($requestBody);
    }

    protected function getResourceStructure(): array
    {
        return [
            'email',
            'token',
        ];
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateWithDuplicatedEmailFail(array $requestBody): void
    {
        $this->createSubscription($requestBody);

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);;
    }

    /**
     * @dataProvider invalidCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateWithInvalidEmailFail(array $requestBody): void
    {
        $this->createSubscription($requestBody);

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422);
    }

    public function testGetSuccess(): void
    {
        $this
            ->actingAs($this->createAdministratorUser())
            ->json('GET', $this->getResourceFullName())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ]);
    }

    public function testGetUnauthorizedFail(): void
    {
        $this
            ->json('GET', $this->getResourceFullName())
            ->assertStatus(401);
    }

    public function testGetPermissionFail(): void
    {
        $this
            ->actingAs($this->createCustomerUser())
            ->json('GET', $this->getResourceFullName())
            ->assertStatus(403);
    }

    public function testDeleteSuccess(): void
    {
        $subscription = $this->createSubscription();

        $this
            ->json('DELETE', "{$this->getResourceFullName()}/{$subscription->token}")
            ->assertStatus(204);

        $this->assertFalse(
            (new Subscription)->newQuery()->whereTokenEquals($subscription->token)->exists(),
            'The subscription was not deleted.'
        );
    }

    protected function getResourceName(): string
    {
        return 'subscriptions';
    }
}
