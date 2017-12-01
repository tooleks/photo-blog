<?php

use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Config\Repository as Config;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
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

    Route::delete('/{photo}')
        ->uses('PhotosController@delete')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,photo');

});

/*
|--------------------------------------------------------------------------
| Posts Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'posts'], function () {

    Route::post('/')
        ->uses('PostsController@create')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', Post::class));

    Route::get('/')
        ->uses('PostsController@paginate');

    Route::get('/{post}')
        ->uses('PostsController@get');

    Route::put('/{post}')
        ->uses('PostsController@update')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,photo');

    Route::delete('/{post}')
        ->uses('PublishedPhotosController@delete')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,photo');

});

/*
|--------------------------------------------------------------------------
| Tags Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'tags'], function () {

    Route::get('/')
        ->uses('TagsController@paginate');

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
        ->uses('SubscriptionsController@paginate')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part');

    Route::delete('/{subscription}')
        ->uses('SubscriptionsController@delete')
        ->middleware('throttle:10,1');

});
