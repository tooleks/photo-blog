<?php

use Api\V1\Http\Middleware\{
    AppendUserId,
    DeletePhotoDirectory,
    FetchExifData,
    GenerateThumbnails,
    UploadPhotoFile
};
use App\Models\DB\{
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
        ->name('token-create')
        ->uses('TokenController@create')
        ->middleware('throttle:20,1'); // Allow 20 requests per minute.

});

/*
|--------------------------------------------------------------------------
| User Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'user'], function () {

    Route::post('/')
        ->uses('UserController@create')
        ->name('user-create')
        ->middleware('can:access-resource,' . User::class . ',');

    Route::get('/{id}')
        ->name('user-get')
        ->uses('UserController@get')
        ->middleware('can:access-resource,' . User::class . ',id');

    Route::put('/{id}')
        ->name('user-update')
        ->uses('UserController@update')
        ->middleware('can:access-resource,' . User::class . ',id');

    Route::delete('/{id}')
        ->name('user-delete')
        ->uses('UserController@delete')
        ->middleware('can:access-resource,' . User::class . ',id');

});

/*
|--------------------------------------------------------------------------
| Uploaded Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'uploaded_photo'], function () {

    Route::post('/')
        ->name('uploaded-photo-create')
        ->uses('UploadedPhotoController@create')
        ->middleware('can:access-resource,' . Photo::class . ',')
        ->middleware(AppendUserId::class)
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::get('/{id}')
        ->name('uploaded-photo-get')
        ->uses('UploadedPhotoController@get')
        ->middleware('can:access-resource,' . Photo::class . ',id');

    Route::post('/{id}')
        ->name('uploaded-photo-update')
        ->uses('UploadedPhotoController@update')
        ->middleware('can:access-resource,' . Photo::class . ',id')
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::delete('/{id}')
        ->name('uploaded-photo-delete')
        ->uses('UploadedPhotoController@delete')
        ->middleware('can:access-resource,' . Photo::class . ',id')
        ->middleware(DeletePhotoDirectory::class);

});

/*
|--------------------------------------------------------------------------
| Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photo'], function () {

    Route::post('/')
        ->name('photo-create')
        ->uses('PhotoController@create')
        ->middleware('can:access-resource,' . Photo::class . ',');

    Route::get('/')
        ->name('photo-get-collection')
        ->uses('PhotoController@getCollection');

    Route::get('/{id}')
        ->name('photo-get')
        ->uses('PhotoController@get');

    Route::put('/{id}')
        ->name('photo-update')
        ->uses('PhotoController@update')
        ->middleware('can:access-resource,' . Photo::class . ',');

    Route::delete('/{id}')
        ->name('photo-delete')
        ->uses('PhotoController@delete')
        ->middleware('can:access-resource,' . Photo::class . ',');

});
