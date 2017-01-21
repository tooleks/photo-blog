<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api.v1" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'token'], function () {

    Route::post('/', [
        'uses' => 'TokenController@create',
        'throttle:20,1',
    ]);

});

Route::group(['prefix' => 'user'], function () {

    Route::post('/', [
        'uses' => 'UserController@create',
        'middleware' => ['can:create,' . \App\Models\DB\User::class],
    ]);

    Route::get('/{user}', [
        'uses' => 'UserController@get',
        'middleware' => ['can:get,user'],
    ]);

    Route::put('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => ['can:update,user'],
    ]);

    Route::delete('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => ['can:delete,user'],
    ]);

});

Route::group(['prefix' => 'uploaded_photo'], function () {

    Route::post('/', [
        'uses' => 'UploadedPhotoController@create',
        'middleware' => [
            'can:create,' . \App\Models\DB\Photo::class,
            \Api\V1\Http\Middleware\AppendUserId::class,
        ],
    ]);

    Route::get('/{photo}', [
        'uses' => 'UploadedPhotoController@get',
    ]);

    Route::post('/{photo}', [
        'uses' => 'UploadedPhotoController@update',
        'middleware' => ['can:update,photo'],
    ]);

    Route::delete('/{photo}', [
        'uses' => 'UploadedPhotoController@delete',
        'middleware' => ['can:delete,photo'],
    ]);

});

Route::group(['prefix' => 'photo'], function () {

    Route::post('/', [
        'uses' => 'PhotoController@create',
        'middleware' => ['can:create,' . \App\Models\DB\Photo::class],
    ]);

    Route::get('/', [
        'uses' => 'PhotoController@getCollection',
    ]);

    Route::get('/{photo}', [
        'uses' => 'PhotoController@get',
    ]);

    Route::put('/{photo}', [
        'uses' => 'PhotoController@update',
        'middleware' => ['can:update,photo'],
    ]);

    Route::delete('/{photo}', [
        'uses' => 'PhotoController@delete',
        'middleware' => ['can:delete,photo'],
    ]);

});
