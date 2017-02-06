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

    Route::post('/', [
        'uses' => 'TokenController@create',
        'middleware' => [
            'throttle:20,1', // Allow 20 requests per minute.
        ],
    ])->name('create_token');

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
    ])->name('create_user');

    Route::get('/{user}', [
        'uses' => 'UserController@get',
        'middleware' => [
            'can:get,user',
        ],
    ])->name('get_user');

    Route::put('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => [
            'can:update,user',
        ],
    ])->name('update_user');

    Route::delete('/{user}', [
        'uses' => 'UserController@update',
        'middleware' => [
            'can:delete,user',
        ],
    ])->name('delete_user');

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
    ])->name('create_uploaded_photo');

    Route::get('/{photo}', [
        'uses' => 'UploadedPhotoController@get',
    ])->name('get_uploaded_photo');

    Route::post('/{photo}', [
        'uses' => 'UploadedPhotoController@update',
        'middleware' => [
            'can:update,photo',
        ],
    ])->name('update_uploaded_photo');

    Route::delete('/{photo}', [
        'uses' => 'UploadedPhotoController@delete',
        'middleware' => [
            'can:delete,photo',
        ],
    ])->name('delete_uploaded_photo');

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
    ])->name('create_photo');

    Route::get('/', [
        'uses' => 'PhotoController@getCollection',
    ])->name('get_photos');

    Route::get('/{photo}', [
        'uses' => 'PhotoController@get',
    ])->name('get_photo');

    Route::put('/{photo}', [
        'uses' => 'PhotoController@update',
        'middleware' => [
            'can:update,photo',
        ],
    ])->name('update_photo');

    Route::delete('/{photo}', [
        'uses' => 'PhotoController@delete',
        'middleware' => [
            'can:delete,photo',
        ],
    ])->name('delete_photo');

});
