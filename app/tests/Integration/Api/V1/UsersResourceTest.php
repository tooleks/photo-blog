<?php

namespace Tests\Integration\Api\V1;

use App\Models\User;

/**
 * Class UsersResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class UsersResourceTest extends TestCase
{
    public function validCreateAttributesProvider(): array
    {
        return [
            [[
                'name' => 'john.doe',
                'email' => 'john.doe@website.domain',
                'password' => 'password',
            ]],
        ];
    }

    public function invalidCreateAttributesProvider(): array
    {
        return [
            [[
                'name' => 'john.doe',
                'email' => 'john.doe_website.domain',
                'password' => 'password',
            ]],
            [[
                'name' => 'john.doe',
            ]],
            [[
                'email' => 'john.doe@website.domain',
            ]],
            [[
                'password' => 'password',
            ]],
            [[
                'name' => 'john.doe',
                'email' => 'john.doe@website.domain',
            ]],
            [[
                'name' => 'john.doe',
                'password' => 'password',
            ]],
            [[
                'email' => 'john.doe@website.domain',
                'password' => 'password',
            ]],
        ];
    }

    public function validUpdateAttributesProvider(): array
    {
        return [
            [[
                'name' => 'john.doe',
                'password' => 'password',
            ]],
            [[
                'name' => 'john.doe',
                'email' => 'john.doe@website.domain',
                'password' => 'password',
            ]],
            [[
                'name' => 'john.doe',
                'email' => 'john.doe@website.domain',
                'password' => 'password',
            ]],
        ];
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateSuccess(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(201)
            ->assertJsonStructure($this->getResourceStructure());
    }

    protected function getResourceStructure(): array
    {
        return [
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @dataProvider invalidCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateValidationSuccess(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422);
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateWithDuplicatedEmailFail(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser($requestBody);

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateUnauthorizedFail(array $requestBody): void
    {
        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(401);
    }

    /**
     * @dataProvider validUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateSuccess(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();
        $user = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$user->id}", $requestBody)
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure());
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateWithDuplicatedEmailFail(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser($requestBody);
        $user = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$user->id}", $requestBody)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    /**
     * @dataProvider validUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateUnauthorizedFail(array $requestBody): void
    {
        $user = $this->createAdministratorUser();

        $this
            ->json('PUT', "{$this->getResourceFullName()}/{$user->id}", $requestBody)
            ->assertStatus(401);
    }

    public function testGetByIdSuccess(): void
    {
        $authUser = $this->createAdministratorUser();
        $user = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('GET', "{$this->getResourceFullName()}/{$user->id}")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure());
    }

    public function testGetByIdUnauthorizedFail(): void
    {
        $user = $this->createAdministratorUser();

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$user->id}")
            ->assertStatus(401);
    }

    public function testDeleteSuccess(): void
    {
        $authUser = $this->createAdministratorUser();
        $user = $this->createAdministratorUser();

        $this
            ->actingAs($authUser)
            ->json('DELETE', "{$this->getResourceFullName()}/{$user->id}")
            ->assertStatus(204);

        $this->assertFalse(
            (new User)->newQuery()->whereIdEquals($user->id)->exists(),
            'The user was not deleted.'
        );
    }

    public function testDeleteUnauthorizedFail(): void
    {
        $user = $this->createAdministratorUser();

        $this
            ->json('DELETE', "{$this->getResourceFullName()}/{$user->id}")
            ->assertStatus(401);
    }

    protected function getResourceName(): string
    {
        return 'users';
    }
}
