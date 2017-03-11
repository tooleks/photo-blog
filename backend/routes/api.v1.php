<?php

use Api\V1\Http\Middleware\{
    AppendIpAddress,
    AppendUserId,
    DeletePhotoDirectory,
    FetchExifData,
    GenerateAvgColor,
    GenerateThumbnails,
    UploadPhotoFile
};
use Core\Models\{
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
| Users Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users'], function () {

    Route::post('/')
        ->uses('UsersController@create')
        ->middleware('can:create-resource,' . User::class);

    Route::get('/{user}')
        ->uses('UsersController@get')
        ->middleware('can:get-resource,user');

    Route::put('/{user}')
        ->uses('UsersController@update')
        ->middleware('can:update-resource,user');

    Route::delete('/{user}')
        ->uses('UsersController@delete')
        ->middleware('can:delete-resource,user');

});

/*
|--------------------------------------------------------------------------
| Photos Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photos'], function () {

    Route::post('/')
        ->uses('PhotosController@create')
        ->middleware('can:create-resource,' . Photo::class)
        ->middleware(AppendUserId::class)
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class)
        ->middleware(GenerateAvgColor::class);

    Route::get('/{photo}')
        ->uses('PhotosController@get')
        ->middleware('can:get-resource,photo');

    Route::post('/{photo}')
        ->uses('PhotosController@update')
        ->middleware('can:update-resource,photo')
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class)
        ->middleware(GenerateAvgColor::class);

    Route::delete('/{photo}')
        ->uses('PhotosController@delete')
        ->middleware('can:delete-resource,photo')
        ->middleware(DeletePhotoDirectory::class);

});

/*
|--------------------------------------------------------------------------
| Published Photos Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'published_photos'], function () {

    Route::post('/')
        ->uses('PublishedPhotosController@create')
        ->middleware('can:create-resource,' . Photo::class);

    Route::get('/')
        ->uses('PublishedPhotosController@find');

    Route::get('/{published_photo}')
        ->uses('PublishedPhotosController@get');

    Route::put('/{published_photo}')
        ->uses('PublishedPhotosController@update')
        ->middleware('can:update-resource,published_photo');

    Route::delete('/{published_photo}')
        ->uses('PublishedPhotosController@delete')
        ->middleware('can:delete-resource,published_photo')
        ->middleware(DeletePhotoDirectory::class);

});

/*
|--------------------------------------------------------------------------
| Tags Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'tags'], function () {

    Route::get('/')
        ->uses('TagsController@find');

});


/*
|--------------------------------------------------------------------------
| Contact Us Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'contact_message'], function () {

    Route::post('/')
        ->uses('ContactMessageController@create')
        ->middleware(AppendIpAddress::class)
        ->middleware('throttle:5,1'); // Allow 5 requests per minute.;

});
