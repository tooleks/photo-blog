<?php

use App\Models\Exif;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\Thumbnail;

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

        return $photo->load('exif', 'tags', 'thumbnails');
    }

    public function testCreateSuccess()
    {
        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $authUser->id,
        ]);

        $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName($this->resourceName), $body = [
                'photo_id' => $photo->id,
                'description' => $this->fake()->realText(),
                'tags' => [
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                    ['value' => strtolower($this->fake()->word)],
                ],
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure)
            ->assertJson([
                'id' => $photo->id,
                'description' => $body['description'],
                'tags' => $body['tags'],
            ]);
    }

    public function testCreateUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
        ]);

        $this
            ->json('POST', $this->getResourceFullName($this->resourceName))
            ->assertStatus(401);
    }

    public function testUpdateSuccess()
    {
        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->actingAs($authUser)
            ->json('PUT', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $photo->id)), $body = [
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
                'description' => $body['description'],
                'tags' => $body['tags'],
            ]);
    }

    public function testUpdateUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('PUT', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $photo->id)))
            ->assertStatus(401);
    }

    public function testGetByIdSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('GET', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $photo->id)))
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
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('GET', $this->getResourceFullName($this->resourceName))
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

    public function testGetByTagSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('GET', $this->getResourceFullName(sprintf('%s?tag=%s', $this->resourceName, $photo->tags->first()->value)))
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

    public function testGetBySearchPhraseSuccess()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('GET', $this->getResourceFullName(sprintf('%s?search_phrase=%s', $this->resourceName, $photo->description . ' ' . $photo->tags->first()->value)))
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
        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $authUser->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->actingAs($authUser)
            ->json('DELETE', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $photo->id)))
            ->assertStatus(204);

        $this->assertFalse(Photo::whereId($photo->id)->exists(), 'The photo was not deleted.');
        $this->assertFalse(Exif::whereId($photo->exif->id)->exists(), 'The exif was not deleted.');
        $photo->tags->each(function ($tag) {
            $this->assertFalse(Tag::whereId($tag->id)->exists(), 'The tag was not deleted.');
        });
        $photo->thumbnails->each(function ($thumbnail) {
            $this->assertFalse(Thumbnail::whereId($thumbnail->id)->exists(), 'The thumbnail was not deleted.');
        });
    }

    public function testDeleteUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto([
            'created_by_user_id' => $user->id,
            'published_at' => \Carbon\Carbon::now(),
        ]);

        $this
            ->json('DELETE', $this->getResourceFullName(sprintf('%s/%s', $this->resourceName, $photo->id)))
            ->assertStatus(401);
    }
}
