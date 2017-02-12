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
        ->middleware('can:create-resource,' . User::class);

    Route::get('/{id}')
        ->uses('UserController@getById')
        ->middleware('can:get-resource,' . User::class . ',id');

    Route::put('/{id}')
        ->uses('UserController@updateById')
        ->middleware('can:update-resource,' . User::class . ',id');

    Route::delete('/{id}')
        ->uses('UserController@deleteById')
        ->middleware('can:delete-resource,' . User::class . ',id');

});

/*
|--------------------------------------------------------------------------
| Uploaded Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'uploaded_photo'], function () {

    Route::post('/')
        ->uses('UploadedPhotoController@create')
        ->middleware('can:create-resource,' . Photo::class)
        ->middleware(AppendUserId::class)
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::get('/{id}')
        ->uses('UploadedPhotoController@getById')
        ->middleware('can:get-resource,' . Photo::class . ',id');

    Route::post('/{id}')
        ->uses('UploadedPhotoController@updateById')
        ->middleware('can:update-resource,' . Photo::class . ',id')
        ->middleware(FetchExifData::class)
        ->middleware(UploadPhotoFile::class)
        ->middleware(GenerateThumbnails::class);

    Route::delete('/{id}')
        ->uses('UploadedPhotoController@deleteById')
        ->middleware('can:delete-resource,' . Photo::class . ',id')
        ->middleware(DeletePhotoDirectory::class);

});

/*
|--------------------------------------------------------------------------
| Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photo'], function () {

    Route::post('/')
        ->uses('PhotoController@create')
        ->middleware('can:create-resource,' . Photo::class);

    Route::get('/')
        ->uses('PhotoController@get');

    Route::get('/{id}')
        ->uses('PhotoController@getById');

    Route::put('/{id}')
        ->uses('PhotoController@updateById')
        ->middleware('can:update-resource,' . Photo::class . ',id');

    Route::delete('/{id}')
        ->uses('PhotoController@deleteById')
        ->middleware('can:delete-resource,' . Photo::class . ',id');

});
