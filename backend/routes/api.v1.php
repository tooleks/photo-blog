<?php

use Api\V1\Http\Middleware\AppendUserId;
use App\Models\DB\Photo;
use App\Models\DB\User;

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


/*
|--------------------------------------------------------------------------
| Token Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'token'], function () {

    Route::post('/', [
        'uses' => 'TokenController@create',
        'middleware' => [
            'throttle:7,1', // Allow 7 requests per minute.
        ],
    ]);

});

/*
|--------------------------------------------------------------------------
| User Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'user'], function () {

    Route::post('/', [
        'uses' => 'UserController@create',
        'middleware' => [
            'can:create,' . User::class,
        ],
    ]);

    Route::get('/{user}', [
        'uses' => 'UserController@get',
        'middleware' => [
            'can:get,user',
        ],
    ]);

    Route::put('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => [
            'can:update,user',
        ],
    ]);

    Route::delete('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => [
            'can:delete,user',
        ],
    ]);

});

/*
|--------------------------------------------------------------------------
| Uploaded Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'uploaded_photo'], function () {

    Route::post('/', [
        'uses' => 'UploadedPhotoController@create',
        'middleware' => [
            'can:create,' . Photo::class,
            AppendUserId::class,
        ],
    ]);

    Route::get('/{photo}', [
        'uses' => 'UploadedPhotoController@get',
    ]);

    Route::post('/{photo}', [
        'uses' => 'UploadedPhotoController@update',
        'middleware' => [
            'can:update,photo',
        ],
    ]);

    Route::delete('/{photo}', [
        'uses' => 'UploadedPhotoController@delete',
        'middleware' => [
            'can:delete,photo',
        ],
    ]);

});

/*
|--------------------------------------------------------------------------
| Photo Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photo'], function () {

    Route::post('/', [
        'uses' => 'PhotoController@create',
        'middleware' => [
            'can:create,' . Photo::class,
        ],
    ]);

    Route::get('/', [
        'uses' => 'PhotoController@getCollection',
    ]);

    Route::get('/{photo}', [
        'uses' => 'PhotoController@get',
    ]);

    Route::put('/{photo}', [
        'uses' => 'PhotoController@update',
        'middleware' => [
            'can:update,photo',
        ],
    ]);

    Route::delete('/{photo}', [
        'uses' => 'PhotoController@delete',
        'middleware' => [
            'can:delete,photo',
        ],
    ]);

});
