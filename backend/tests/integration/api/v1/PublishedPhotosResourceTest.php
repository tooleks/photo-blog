<?php

use Core\Models\Exif;
use Core\Models\Photo;
use Core\Models\Tag;
use Core\Models\Thumbnail;

/**
 * Class PublishedPhotosResourceTest.
 */
class PublishedPhotosResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'published_photos';

    protected $resourceStructure = [
        'id',
        'created_by_user_id',
        'url',
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
            [
                'value',
            ],
        ],
    ];

    protected function createTestPhoto(array $attributes = [])
    {
        $photo = factory(Photo::class)->create($attributes);
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->exif()->save(factory(Exif::class)->make());
        $photo->tags()->save(factory(Tag::class)->make());

        return $photo;
    }

    public function testCreateSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => false,
        ]);

        $this
            ->actingAs($user)
            ->json('POST', sprintf('/%s', $this->resourceName), $data = [
                'photo_id' => $photo->id,
                'description' => $this->fake()->realText(),
                'tags' => [
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                ],
            ])
            ->assertJson([
                'id' => $photo->id,
                'description' => $data['description'],
                'tags' => $data['tags'],
            ]);
    }

    public function testCreateUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => false,
        ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName))
            ->assertStatus(401);
    }

    public function testUpdateSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->actingAs($user)
            ->json('PUT', sprintf('/%s/%s', $this->resourceName, $photo->id), $data = [
                'description' => $this->fake()->realText(),
                'tags' => [
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                ],
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'id' => $photo->id,
                'description' => $data['description'],
                'tags' => $data['tags'],
            ]);
    }

    public function testUpdateUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->json('PUT', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(401);
    }

    public function testGetByIdSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->json('GET', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'id' => $photo->id,
                'description' => $photo->description,
                'tags' => array_map(function ($tag) {
                    return ['value' => $tag['value']];
                }, $photo->tags->toArray()),
            ]);
    }

    public function testGetSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->json('GET', sprintf('/%s', $this->resourceName))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    $this->resourceStructure,
                ],
            ])
            ->assertJson([
                'data' => [
                    [
                        'id' => $photo->id,
                        'description' => $photo->description,
                        'tags' => array_map(function ($tag) {
                            return ['value' => $tag['value']];
                        }, $photo->tags->toArray()),
                    ],
                ],
            ]);
    }

    public function testDeleteSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->actingAs($user)
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(204);

        $this->assertFalse(Photo::whereId($photo->id)->exists());
    }

    public function testDeleteUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'is_published' => true,
        ]);

        $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(401);
    }
}
