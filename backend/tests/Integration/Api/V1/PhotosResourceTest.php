<?php

namespace Tests\Integration\Api\V1;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class PhotosResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class PhotosResourceTest extends TestCase
{
    protected function getResourceName(): string
    {
        return 'photos';
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
        ];
    }

    protected function getPathFromUrl(string $url): string
    {
        return str_after($url, '/storage/');
    }

    public function validCreateAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [['file' => UploadedFile::fake()->image('photo.jpg', 600, 600)->size(500)]],
        ];
    }

    public function invalidCreateAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [[]],
            [['file' => UploadedFile::fake()->create('photo.txt', 0)]],
        ];
    }

    public function validUpdateAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [['file' => UploadedFile::fake()->image('photo.jpg', 1000, 1000)->size(500)]],
        ];
    }

    public function invalidUpdateAttributesProvider(): array
    {
        $this->refreshApplication();

        return [
            [[]],
            [['file' => UploadedFile::fake()->create('photo.txt', 0)]],
        ];
    }

    /**
     * @dataProvider validCreateAttributesProvider
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

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($responseBody['thumbnails']['medium']['url']),
            'The photo thumbnail file was not generated.'
        );

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($responseBody['thumbnails']['large']['url']),
            'The photo thumbnail file was not generated.'
        );
    }

    /**
     * @dataProvider invalidCreateAttributesProvider
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
     * @dataProvider validCreateAttributesProvider
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

    /**
     * @dataProvider validUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateSuccess(array $requestBody): void
    {
        Storage::fake('public');

        $authUser = $this->createAdministratorUser();
        $photo = $this->createPhoto(['created_by_user_id' => $authUser->id]);

        $responseBody = $this
            ->actingAs($authUser)
            ->json('POST', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(200)
            ->assertJsonStructure($this->getResourceStructure())
            ->decodeResponseJson();

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($responseBody['thumbnails']['medium']['url']),
            'The photo thumbnail file was not generated.'
        );

        Storage::disk('public')->assertExists(
            $this->getPathFromUrl($responseBody['thumbnails']['large']['url']),
            'The photo thumbnail file was not generated.'
        );
    }

    /**
     * @dataProvider invalidUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateValidationFail(array $requestBody): void
    {
        Storage::fake('public');

        $authUser = $this->createAdministratorUser();
        $photo = $this->createPhoto(['created_by_user_id' => $authUser->id]);

        $this
            ->actingAs($authUser)
            ->json('POST', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(422);
    }

    /**
     * @dataProvider validUpdateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testUpdateUnauthorized(array $requestBody): void
    {
        Storage::fake('public');

        $user = $this->createAdministratorUser();
        $photo = $this->createPhoto(['created_by_user_id' => $user->id]);

        $this
            ->json('POST', "{$this->getResourceFullName()}/{$photo->id}", $requestBody)
            ->assertStatus(401);
    }
}
