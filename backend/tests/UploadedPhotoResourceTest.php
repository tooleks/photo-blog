<?php

use Api\V1\Http\Resources\UploadedPhotoResource;
use App\Models\DB\Photo;
use App\Models\DB\Thumbnail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * Class UploadedPhotoResourceTest
 */
class UploadedPhotoResourceTest extends TestCase
{
    public function testCreateUploadedPhoto()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $attributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $photo = $uploadedPhotoResource->create($attributes);

        $this->assertInstanceOf(Photo::class, $photo, 'It should be an instance of Photo.');

        $this->assertArrayHasKey('id', $photo->toArray(), 'It should include an id attribute.');
        $this->assertArrayHasKey('created_at', $photo->toArray(), 'It should include a created_at attribute.');
        $this->assertArrayHasKey('updated_at', $photo->toArray(), 'It should include a updated_at attribute.');

        $this->assertEquals($photo->user_id, $attributes['user_id'], 'It should be the same user ids.');
        $this->assertEquals($photo->path, $attributes['path'], 'It should be the same paths.');
        $this->assertEquals($photo->relative_url, $attributes['relative_url'], 'It should be the same relative_urls.');
        $this->assertEquals($photo->is_published, false, 'It should be equal to false.');

        $this->assertEquals($photo->thumbnails[0]['path'], $attributes['thumbnails'][0]['path'], 'It should be the same paths.');
        $this->assertEquals($photo->thumbnails[0]['relative_url'], $attributes['thumbnails'][0]['relative_url'], 'It should be the same relative_urls.');
        $this->assertEquals($photo->thumbnails[0]['width'], $attributes['thumbnails'][0]['width'], 'It should be the same widths.');
        $this->assertEquals($photo->thumbnails[0]['height'], $attributes['thumbnails'][0]['height'], 'It should be the same heights.');
    }

    public function testCreateUploadedPhotoWithEmptyAttributes()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $this->expectException(ValidationException::class);

        try {
            $uploadedPhotoResource->create([]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->messages();
            throw $e;
        }

        $this->assertArrayHasKey('user_id', $errors ?? [], 'It should include a user_id error.');
        $this->assertArrayHasKey('path', $errors ?? [], 'It should include a path error.');
        $this->assertArrayHasKey('relative_url', $errors ?? [], 'It should include a relative_url error.');
        $this->assertArrayHasKey('thumbnails', $errors ?? [], 'It should include a thumbnails error.');
    }

    public function testUpdateUploadedPhoto()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $firstPhotoAttributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $secondPhotoAttributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $firstPhoto = $uploadedPhotoResource->create($firstPhotoAttributes);

        $secondPhoto = $uploadedPhotoResource->update($firstPhoto, $secondPhotoAttributes);

        $this->assertInstanceOf(Photo::class, $secondPhoto, 'It should be an instance of User.');

        $this->assertNotEquals($secondPhoto->user_id, $secondPhotoAttributes['user_id'], 'It should be not the same user ids.');
        $this->assertEquals($secondPhoto->path, $secondPhotoAttributes['path'], 'It should be the same paths.');
        $this->assertEquals($secondPhoto->relative_url, $secondPhotoAttributes['relative_url'], 'It should be the same relative_urls.');
        $this->assertEquals($secondPhoto->is_published, false, 'It should be equal to false.');

        $this->assertEquals($secondPhoto->thumbnails[0]['path'], $secondPhotoAttributes['thumbnails'][0]['path'], 'It should be the same paths.');
        $this->assertEquals($secondPhoto->thumbnails[0]['relative_url'], $secondPhotoAttributes['thumbnails'][0]['relative_url'], 'It should be the same relative_urls.');
        $this->assertEquals($secondPhoto->thumbnails[0]['width'], $secondPhotoAttributes['thumbnails'][0]['width'], 'It should be the same widths.');
        $this->assertEquals($secondPhoto->thumbnails[0]['height'], $secondPhotoAttributes['thumbnails'][0]['height'], 'It should be the same heights.');
    }

    public function testDeleteUploadedPhoto()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $attributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $photo = $uploadedPhotoResource->create($attributes);

        $count = $uploadedPhotoResource->delete($photo);

        $this->assertEquals($count, 1, 'It should be equal "1".');
    }

    public function testDeleteNotExistingUploadedPhoto()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $attributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $photo = $uploadedPhotoResource->create($attributes);

        $uploadedPhotoResource->delete($photo);

        $count = $uploadedPhotoResource->delete($photo);

        $this->assertEquals($count, 0, 'It should be equal "0".');
    }

    public function testGetUserById()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $attributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $photoCreated = $uploadedPhotoResource->create($attributes);

        $photoFound = $uploadedPhotoResource->getById($photoCreated->id);

        $this->assertInstanceOf(Photo::class, $photoFound, 'It should be an instance of Photo.');

        $this->assertEquals($photoCreated->id, $photoFound->id, 'It should be the same ids.');
        $this->assertEquals($photoCreated->user_id, $photoFound->user_id, 'It should be the same user ids.');
        $this->assertEquals($photoCreated->path, $photoFound->path, 'It should be the same paths.');
        $this->assertEquals($photoCreated->relative_url, $photoFound->relative_url, 'It should be the same relative_urls.');
        $this->assertEquals($photoCreated->is_published, false, 'It should be equal to false.');
        $this->assertEquals($photoCreated->created_at, $photoFound->created_at, 'It should be the same dates.');
        $this->assertEquals($photoCreated->updated_at, $photoFound->updated_at, 'It should be the same dates.');

        $this->assertEquals($photoCreated->thumbnails[0]['path'], $photoFound->thumbnails[0]['path'], 'It should be the same paths.');
        $this->assertEquals($photoCreated->thumbnails[0]['relative_url'], $photoFound->thumbnails[0]['relative_url'], 'It should be the same relative_urls.');
        $this->assertEquals($photoCreated->thumbnails[0]['width'], $photoFound->thumbnails[0]['width'], 'It should be the same widths.');
        $this->assertEquals($photoCreated->thumbnails[0]['height'], $photoFound->thumbnails[0]['height'], 'It should be the same heights.');

    }

    public function testGetNotExistingUserById()
    {
        $uploadedPhotoResource = $this->app->make(UploadedPhotoResource::class);

        $attributes = factory(Photo::class)->make()->toArray() + ['thumbnails' => [
                factory(Thumbnail::class)->make()->toArray(),
            ]];

        $photo = $uploadedPhotoResource->create($attributes);

        $uploadedPhotoResource->delete($photo);

        $this->expectException(ModelNotFoundException::class);

        $uploadedPhotoResource->getById($photo->id);
    }
}
