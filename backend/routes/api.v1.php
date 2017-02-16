<?php

use Api\V1\Http\Middleware\{
    AppendUserId,
    DeletePhotoDirectory,
    FetchExifData,
    GenerateThumbnails,
    UploadPhotoFile
};
use Core\DAL\Models\{
    Photo,
    User
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the Api\V1\Providers\RouteServiceProvider within a group which
| is assigned the "api.v1" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return [
        'name' => config('app.name'),
        'version' => '1.0.0',
    ];
});

/*
|--------------------------------------------------------------------------
| Token Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'token'], function () {

    Route::post('/')
        ->uses('TokenController@create')
        ->middleware('throttle:20,1'); // Allow 20 requests per minute.

});

/*
|--------------------------------------------------------------------------
| User Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users'], function () {

    Route::post('/')
        ->uses('UserController@create')
        ->middleware('can:create-resource,' . User::class);

    Route::get('/{user}')
        ->uses('UserController@get')
        ->middleware('can:get-resource,user');

    Route::put('/{user}')
        ->uses('UserController@update')
        ->middleware('can:update-resource,user');

    Route::delete('/{user}')
        ->uses('UserController@delete')
        ->middleware('can:delete-resource,user');

});

/*
|--------------------------------------------------------------------------
| Uploaded Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photos'], function () {

    Route::post('/')
        ->uses('PhotoController@create')
        ->middleware('can:create-resource,' . Photo::class)
        ->middleware(AppendUserId::class)
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::get('/{photo}')
        ->uses('PhotoController@get')
        ->middleware('can:get-resource,photo');

    Route::post('/{photo}')
        ->uses('PhotoController@update')
        ->middleware('can:update-resource,photo')
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::delete('/{photo}')
        ->uses('PhotoController@delete')
        ->middleware('can:delete-resource,photo')
        ->middleware(DeletePhotoDirectory::class);

});

/*
|--------------------------------------------------------------------------
| Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'published_photos'], function () {

    Route::post('/')
        ->uses('PublishedPhotoController@create')
        ->middleware('can:create-resource,' . Photo::class);

    Route::get('/')
        ->uses('PublishedPhotoController@find');

    Route::get('/{published_photo}')
        ->uses('PublishedPhotoController@get');

    Route::put('/{published_photo}')
        ->uses('PublishedPhotoController@update')
        ->middleware('can:update-resource,published_photo');

    Route::delete('/{published_photo}')
        ->uses('PublishedPhotoController@delete')
        ->middleware('can:delete-resource,published_photo')
        ->middleware(DeletePhotoDirectory::class);

});
