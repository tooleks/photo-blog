<?php

namespace Tests\Integration\Api\V1;

use App\Models\Photo;
use App\Models\Thumbnail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class PhotosResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class PhotosResourceTest extends TestCase
{
    public function validAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [['file' => UploadedFile::fake()->image('photo.jpg', 600, 600)->size(500)]],
        ];
    }

    public function invalidAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [[]],
            [['file' => UploadedFile::fake()->create('photo.txt', 0)]],
        ];
    }

    /**
     * @dataProvider validAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateSuccess(array $requestBody): void
    {
        Storage::fake('public');

        $authUser = $this->createAdministratorUser();

        $responseBody = $this
            ->actingAs($authUser)
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(201)
            ->assertJsonStructure($this->getResourceStructure())
            ->decodeResponseJson();

        /** @var Photo $photo */
        $photo = (new Photo)
            ->newQuery()
            ->withEntityRelations()
            ->whereIdEquals($responseBody['id'])
            ->firstOrFail();

        Storage::assertExists($photo->path);
        $photo->thumbnails->each(function (Thumbnail $thumbnail) {
            Storage::assertExists($thumbnail->path);
        });
    }

    protected function getResourceStructure(): array
    {
        return [
            'id',
            'created_by_user_id',
            'avg_color',
            'created_at',
            'exif' => [
                'manufacturer',
                'model',
                'exposure_time',
                'aperture',
                'iso',
                'taken_at',
                'software',
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
    }

    /**
     * @dataProvider invalidAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateValidationFail(array $requestBody): void
    {
        Storage::fake('public');

        $authUser = $this->createAdministratorUser();

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
    public function testCreateUnauthorized(array $requestBody): void
    {
        Storage::fake('public');

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(401);
    }

    public function testDeleteSuccess(): void
    {
        Storage::fake('public');

        $authUser = $this->createAdministratorUser();
        $photo = $this->createPhoto(['created_by_user_id' => $authUser->id]);

        $this
            ->actingAs($authUser)
            ->json('DELETE', "{$this->getResourceFullName()}/{$photo->id}")
            ->assertStatus(204);
    }

    public function testDeleteUnauthorized(): void
    {
        Storage::fake('public');

        $user = $this->createAdministratorUser();
        $photo = $this->createPhoto(['created_by_user_id' => $user->id]);

        $this
            ->json('DELETE', "{$this->getResourceFullName()}/{$photo->id}")
            ->assertStatus(401);

        Storage::assertMissing($photo->path);
        $photo->thumbnails->each(function (Thumbnail $thumbnail) {
            Storage::assertMissing($thumbnail->path);
        });
    }

    protected function getResourceName(): string
    {
        return 'photos';
    }
}
