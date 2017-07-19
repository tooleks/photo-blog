<?php

use App\Models\Exif;
use App\Models\Photo;
use App\Models\Thumbnail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class PhotosResourceTest.
 */
class PhotosResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'photos';

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
    ];

    protected function createTestPhoto(array $attributes = [])
    {
        $photo = factory(Photo::class)->create($attributes);
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->exif()->save(factory(Exif::class)->make());

        return $photo->load('exif', 'thumbnails');
    }

    protected function getPathFromUrl($url)
    {
        $storageUrl = sprintf('%s/storage/', config('main.storage.url'));
        $path = str_replace($storageUrl, '', $url);
        return $path;
    }

    public function testCreateSuccess()
    {
        Storage::fake('public');

        $authUser = $this->createTestUser();

        $response = $this
            ->actingAs($authUser)
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'file' => UploadedFile::fake()->image('photo.jpg', 1000, 1000)->size(500),
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure);

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->url),
            'The photo file was not saved.'
        );
        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->thumbnails->medium->url),
            'The photo thumbnail file was not generated.'
        );
        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->thumbnails->large->url),
            'The photo thumbnail file was not generated.'
        );
    }

    public function testCreateUnauthorized()
    {
        Storage::fake('public');

        $this
            ->json('POST', sprintf('/%s', $this->resourceName))
            ->assertStatus(401);
    }

    public function testUpdateSuccess()
    {
        Storage::fake('public');

        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $authUser->id]);

        $response = $this
            ->actingAs($authUser)
            ->json('POST', sprintf('/%s/%s', $this->resourceName, $photo->id), [
                'file' => UploadedFile::fake()->image('photo.jpg', 1000, 1000)->size(500),
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure);

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->url),
            'The photo file was not saved.'
        );
        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->thumbnails->medium->url),
            'The photo thumbnail file was not generated.'
        );
        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($response->getData()->thumbnails->large->url),
            'The photo thumbnail file was not generated.'
        );
    }

    public function testUpdateUnauthorized()
    {
        Storage::fake('public');

        $user = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $user->id]);

        $this
            ->json('POST', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(401);
    }

    public function testDeleteSuccess()
    {
        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $authUser->id]);

        $this
            ->actingAs($authUser)
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(204);

        $this->assertFalse(Photo::whereId($photo->id)->exists(), 'The photo was not deleted.');
        $this->assertFalse(Exif::whereId($photo->exif->id)->exists(), 'The exif was not deleted.');
        $photo->thumbnails->each(function ($thumbnail) {
            $this->assertFalse(Thumbnail::whereId($thumbnail->id)->exists(), 'The thumbnail was not deleted.');
        });
    }

    public function testDeleteUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $user->id]);

        $this
            ->json('DELETE', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(401);
    }

    public function testGetByIdSuccess()
    {
        $authUser = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $authUser->id]);

        $this
            ->actingAs($authUser)
            ->json('GET', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceStructure);
    }

    public function testGetByIdUnauthorized()
    {
        $user = $this->createTestUser();
        $photo = $this->createTestPhoto(['created_by_user_id' => $user->id]);

        $this
            ->json('GET', sprintf('/%s/%s', $this->resourceName, $photo->id))
            ->assertStatus(401);
    }
}
