<?php

namespace Tests\Integration\Api\V1;

use App\Models\Photo;
use App\Models\Tag;
use Carbon\Carbon;

/**
 * Class PublishedPhotosResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class PublishedPhotosResourceTest extends TestCase
{
    protected function getResourceName(): string
    {
        return 'published_photos';
    }

    protected function getResourceStructure(): array
    {
        return [
            'id',
            'created_by_user_id',
            'avg_color',
            'created_at',
            'updated_at',
            'exif' => [
                'manufacturer',
                'model',
                'exposure_time',
                'aperture',
                'iso',
                'taken_at',
            ],
            'thumbnails' => [
                'medium' => [
                    'url',
                    'width',
                    'height',
                ],
                'large' => [
                    'url',
                    'width',
                    'height',
                ],
            ],
            'tags' => [
                '*' => [
                    'value',
                ],
            ],
        ];
    }

    public function validCreateAttributesProvider(): array
    {
        return [
            [[
                'description' => 'The photo description.',
                'tags' => [
                    ['value' => 'sky'],
                    ['value' => 'water'],
                ],
            ]],
        ];
    }

    public function invalidCreateAttributesProvider(): array
    {
        return [
            [[
                'description' => 'The photo description.',
            ]],
            [[
                'tags' => [
                    ['value' => 'sky'],
                    ['value' => 'water'],
                ],
            ]],
            [[
                'description' => 'The photo description.',
                'tags' => [
                    ['value' => ''],
                ],
            ]],
            [[
                'description' => '',
                'tags' => [
                    ['value' => 'sky'],
                ],
            ]],
            [[
                'description' => '',
                'tags' => [
                    ['key' => 'sky'],
                ],
            ]],
        ];
    }

    public function validUpdateAttributesProvider(): array
    {
        return [
            [[
                'description' => 'The photo description.',
                'tags' => [
                    ['value' => 'sky'],
                    ['value' => 'water'],
                ],
            ]],
            [[
                'description' => 'Updated photo description.',
            ]],
            [[
                'tags' => [
                    ['value' => 'skyline'],
                ],
            ]],
        ];
    }

    public function invalidUpdateAttributesProvider(): array
    {
        return [
            [[
                'description' => '',
                'tags' => [
                    ['value' => 'sky'],
                ],
            ]],
            [[
                'description' => '',
                'tags' => [
                    ['key' => 'sky'],
                ],
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
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => null,
        ]);

        $requestBody['photo_id'] = $photo->id;

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(201)
            ->assertJsonStructure($this->getResourceStructure());
    }

    /**
     * @dataProvider invalidCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateValidationFail(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => null,
        ]);

        $requestBody['photo_id'] = $photo->id;

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
    public function testCreateUnauthorizedFail(array $requestBody): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => null,
        ]);

        $requestBody['photo_id'] = $photo->id;

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
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure());
    }

    /**
     * @dataProvider invalidUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateValidationFail(array $requestBody): void
    {
        $authUser = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->actingAs($authUser)
            ->json('PUT', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(422);
    }

    /**
     * @dataProvider validUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateUnauthorized(array $requestBody): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->json('PUT', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(401);
    }

    public function testGetByIdSuccess(): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->json('GET', "{$this->getResourceFullName()}/{$photo->id}")
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->assertJson([
                'id' => $photo->id,
                'description' => $photo->description,
                'tags' => array_map(function ($tag) {
                    return ['value' => $tag['value']];
                }, $photo->tags->toArray()),
            ]);
    }

    public function testGetSuccess(): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->json('GET', "{$this->getResourceFullName()}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'id' => $photo->id,
                        'description' => $photo->description,
                        'tags' => $photo->tags->map(function (Tag $tag) {
                            return ['value' => $tag->value];
                        })->toArray(),
                    ],
                ],
            ]);
    }

    public function testGetByTagSuccess(): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $tag = $photo->tags->first()->value;

        $this
            ->json('GET', "{$this->getResourceFullName()}?tag={$tag}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'id' => $photo->id,
                        'description' => $photo->description,
                        'tags' => $photo->tags->map(function (Tag $tag) {
                            return ['value' => $tag->value];
                        })->toArray(),
                    ],
                ],
            ]);
    }

    public function testGetBySearchPhraseSuccess(): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $searchPhrase = $photo->description . ' ' . $photo->tags->first()->value;

        $this
            ->json('GET', "{$this->getResourceFullName()}?searchPhrase={$searchPhrase}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'id' => $photo->id,
                        'description' => $photo->description,
                        'tags' => $photo->tags->map(function (Tag $tag) {
                            return ['value' => $tag->value];
                        })->toArray(),
                    ],
                ],
            ]);
    }

    public function testDeleteSuccess(): void
    {
        $authUser = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->actingAs($authUser)
            ->json('DELETE', "{$this->getResourceFullName()}/{$photo->id}")
            ->assertStatus(204);

        $this->assertFalse(
            (new Photo)->newQuery()->whereIdEquals($photo->id)->exists(),
            'The photo was not deleted.'
        );
    }

    public function testDeleteUnauthorized(): void
    {
        $user = $this->createAdministratorUser();
        $photo = $this->createPublishedPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => Carbon::now(),
        ]);

        $this
            ->json('DELETE', "{$this->getResourceFullName()}/{$photo->id}")
            ->assertStatus(401);
    }
}
