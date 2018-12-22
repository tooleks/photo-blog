<?php

use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/')
    ->uses(\Api\V1\Http\Actions\InfoGetAction::class);

/*
|--------------------------------------------------------------------------
| Auth Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {

    Route::post('/token')
        ->middleware('throttle:10,1')
        ->uses(\Api\V1\Http\Actions\AuthCreateAction::class);

    Route::delete('/token')
        ->middleware('auth:api')
        ->middleware('throttle:10,1')
        ->uses(\Api\V1\Http\Actions\AuthDeleteAction::class);

});

/*
|--------------------------------------------------------------------------
| Users Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users'], function () {

    Route::post('/')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', User::class))
        ->uses(\Api\V1\Http\Actions\UserCreateAction::class);

    Route::get('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:get-resource,user')
        ->uses(\Api\V1\Http\Actions\UserGetByIdAction::class);

    Route::put('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,user')
        ->uses(\Api\V1\Http\Actions\UserUpdateByIdAction::class);

    Route::delete('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,user')
        ->uses(\Api\V1\Http\Actions\UserDeleteByIdAction::class);

});

/*
|--------------------------------------------------------------------------
| Photos Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'photos'], function () {

    Route::post('/')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', Photo::class))
        ->uses(\Api\V1\Http\Actions\PhotoCreateAction::class);

    Route::put('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:update-resource,%s', Photo::class))
        ->uses(\Api\V1\Http\Actions\PhotoUpdateByIdAction::class);

    Route::delete('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,photo')
        ->uses(\Api\V1\Http\Actions\PhotoDeleteByIdAction::class);

});

/*
|--------------------------------------------------------------------------
| Posts Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'posts'], function () {

    Route::post('/')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware(sprintf('can:create-resource,%s', Post::class))
        ->uses(\Api\V1\Http\Actions\PostCreateAction::class);

    Route::get('/')
        ->uses(\Api\V1\Http\Actions\PostPaginateAction::class);

    Route::get('/{id}')
        ->uses(\Api\V1\Http\Actions\PostGetByIdAction::class);

    Route::get('/{id}/previous')
        ->uses(\Api\V1\Http\Actions\PostGetBeforeIdAction::class);

    Route::get('/{id}/next')
        ->uses(\Api\V1\Http\Actions\PostGetAfterIdAction::class);

    Route::put('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:update-resource,photo')
        ->uses(\Api\V1\Http\Actions\PostUpdateByIdAction::class);

    Route::delete('/{id}')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->middleware('can:delete-resource,photo')
        ->uses(\Api\V1\Http\Actions\PostDeleteByIdAction::class);

});

/*
|--------------------------------------------------------------------------
| Tags Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'tags'], function () {

    Route::get('/')
        ->uses(\Api\V1\Http\Actions\TagPaginateAction::class);

});

/*
|--------------------------------------------------------------------------
| Contact Message Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'contact_messages'], function () {

    Route::post('/')
        ->middleware('throttle:10,1')
        ->uses(\Api\V1\Http\Actions\ContactMessageSendAction::class);

});

/*
|--------------------------------------------------------------------------
| Subscription Resource Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'subscriptions'], function () {

    Route::post('/')
        ->middleware('throttle:10,1')
        ->uses(\Api\V1\Http\Actions\SubscriptionCreateAction::class);

    Route::get('/')
        ->middleware('auth:api')
        ->middleware('can:access-administrator-part')
        ->uses(\Api\V1\Http\Actions\SubscriptionPaginateAction::class);

    Route::delete('/{token}')
        ->middleware('throttle:10,1')
        ->uses(\Api\V1\Http\Actions\SubscriptionDeleteByTokenAction::class);

});
