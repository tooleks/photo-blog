<?php

namespace Tests\Integration\Api\V1;

/**
 * Class PostsResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class PostsResourceTest extends TestCase
{
    public function validAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [[
                'is_published' => true,
                'description' => $this->getFake()->sentence,
                'tags' => [
                    ['value' => $this->getFake()->word],
                ],
            ]],
        ];
    }

    public function invalidAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [[]],
        ];
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateSuccess(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(201)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJson($requestBody);
    }

    protected function getResourceStructure(): array
    {
        return [
            'id',
            'created_by_user_id',
            'is_published',
            'description',
            'published_at',
            'created_at',
            'updated_at',
            'photo',
            'tags',
        ];
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreatePermissionFail(array $requestBody): void
    {
        $authUser = $this->createCustomerUser();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(403);
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateUnauthorizedFail(array $requestBody): void
    {
        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(401);
    }

    /**
     * @dataProvider invalidAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateValidationFail(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422);
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateSuccess(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();
        $post = $this->createPost();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$post->id}", $requestBody)
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJson($requestBody);
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdatePermissionFail(array $requestBody): void
    {
        $authUser = $this->createCustomerUser();
        $post = $this->createPost();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$post->id}", $requestBody)
            ->assertStatus(403);
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateUnauthorizedFail(array $requestBody): void
    {
        $post = $this->createPost();

        $requestBody['photo']['id'] = $this->createPhoto()->id;

        $this
            ->json('PUT', "{$this->getResourceFullName()}/{$post->id}", $requestBody)
            ->assertStatus(401);
    }

    public function testGetPublishedSuccess(): void
    {
        $post = $this->createPost();

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJsonFragment(['id' => $post->id]);
    }

    public function testGetPreviousPublishedSuccess(): void
    {
        $previousPost = $this->createPost();
        $post = $this->createPost();
        $nextPost = $this->createPost();

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}/previous")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJsonFragment(['id' => $previousPost->id]);
    }

    public function testGetNextPublishedSuccess(): void
    {
        $previousPost = $this->createPost();
        $post = $this->createPost();
        $nextPost = $this->createPost();

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}/next")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJsonFragment(['id' => $nextPost->id]);
    }

    public function testGetNotPublishedAsAdministratorSuccess(): void
    {
        $authUser = $this->createAdministratorUser();
        $post = $this->createPost(['is_published' => false]);

        $this
            ->actingAs($authUser)
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure());
    }

    public function testGetNotPublishedAsCustomerNotFoundFail(): void
    {
        $authUser = $this->createCustomerUser();
        $post = $this->createPost(['is_published' => false]);

        $this
            ->actingAs($authUser)
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(404);
    }

    public function testPaginateSuccess(): void
    {
        $this
            ->json('GET', "{$this->getResourceFullName()}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ]);
    }

    public function testPaginateBySearchPhraseSuccess(): void
    {
        $this->createPost();
        $searchPhrase = $this->createPost(['description' => 'description'])->description;
        $this->createPost();

        $this
            ->json('GET', "{$this->getResourceFullName()}/?search_phrase={$searchPhrase}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'description' => $searchPhrase,
                    ],
                ],
            ]);
    }

    public function testPaginateByTagPhraseSuccess(): void
    {
        $this->createPost();
        $tag = $this->createPost()->tags->first()->value;
        $this->createPost();

        $this
            ->json('GET', "{$this->getResourceFullName()}/?tag={$tag}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'tags' => [
                            [
                                'value' => $tag,
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testGetNotPublishedAsUnauthenticatedNotFoundFail(): void
    {
        $post = $this->createPost(['is_published' => false]);

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(404);
    }

    public function testDeleteSuccess(): void
    {
        $authUser = $this->createAdministratorUser();
        $post = $this->createPost();

        $this
            ->actingAs($authUser)
            ->json('DELETE', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(204);
    }

    public function testDeletePermissionFail(): void
    {
        $authUser = $this->createCustomerUser();
        $post = $this->createPost();

        $this
            ->actingAs($authUser)
            ->json('DELETE', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(403);
    }

    public function testDeleteUnauthorizedFail(): void
    {
        $post = $this->createPost();

        $this
            ->json('DELETE', "{$this->getResourceFullName()}/{$post->id}")
            ->assertStatus(401);
    }

    protected function getResourceName(): string
    {
        return 'posts';
    }
}
