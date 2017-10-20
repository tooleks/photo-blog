<?php

use App\Models\Photo;
use App\Models\User;
use Illuminate\Contracts\Config\Repository as Config;

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

Route::get('/', function (Config $config) {
    return [
        'name' => $config->get('app.name'),
        'version' => '1',
    ];
});

/*
|--------------------------------------------------------------------------
| Auth Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {

    Route::post('/token')
        ->uses('AuthController@create')
        ->middleware('throttle:10,1');

    Route::delete('/token')
        ->uses('AuthController@delete')
        ->middleware('auth:api')
        ->middleware('throttle:10,1');

});

/*
|--------------------------------------------------------------------------
| Users Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users'], function () {

    Route::post('/')
        ->uses('UsersController@create')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', User::class));

    Route::get('/{user}')
        ->uses('UsersController@get')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:get-resource,user');

    Route::put('/{user}')
        ->uses('UsersController@update')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,user');

    Route::delete('/{user}')
        ->uses('UsersController@delete')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
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
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', Photo::class));

    Route::get('/{photo}')
        ->uses('PhotosController@get')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:get-resource,photo');

    Route::post('/{photo}')
        ->uses('PhotosController@update')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,photo');

    Route::delete('/{photo}')
        ->uses('PhotosController@delete')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,photo');

});

/*
|--------------------------------------------------------------------------
| Published Photos Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'published_photos'], function () {

    Route::post('/')
        ->uses('PublishedPhotosController@create')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', Photo::class));

    Route::get('/')
        ->uses('PublishedPhotosController@find');

    Route::get('/{published_photo}')
        ->uses('PublishedPhotosController@get');

    Route::put('/{published_photo}')
        ->uses('PublishedPhotosController@update')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,published_photo');

    Route::delete('/{published_photo}')
        ->uses('PublishedPhotosController@delete')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,published_photo');

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
| Contact Message Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'contact_messages'], function () {

    Route::post('/')
        ->uses('ContactMessagesController@create')
        ->middleware('throttle:10,1');

});

/*
|--------------------------------------------------------------------------
| Subscription Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'subscriptions'], function () {

    Route::post('/')
        ->uses('SubscriptionsController@create')
        ->middleware('throttle:10,1');

    Route::get('/')
        ->uses('SubscriptionsController@find')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part');

    Route::delete('/{subscription}')
        ->uses('SubscriptionsController@delete')
        ->middleware('throttle:10,1');

});
