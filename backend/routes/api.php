<?php

use App\Http\Middleware\SearchParametersSpreader;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/photos/', [
    'uses' => 'PhotoController@all',
]);

Route::get('/photos/search', [
    'uses' => 'PhotoController@search',
    'middleware' => [
        SearchParametersSpreader::class
    ],
]);

Route::get('/photo/{photo}', [
    'uses' => 'PhotoController@view',
]);

Route::post('/photo', [
    'uses' => 'PhotoController@create',
    'middleware' => ['can:create,App\Models\Photo'],
]);

Route::post('/photo/upload', [
    'uses' => 'PhotoController@upload',
    'middleware' => ['can:create,App\Models\Photo'],
]);

Route::put('/photo/{photo}', [
    'uses' => 'PhotoController@update',
    'middleware' => ['can:update,photo'],
]);

Route::post('/photo/{photo}/upload', [
    'uses' => 'PhotoController@upload',
    'middleware' => ['can:update,photo'],
]);

Route::delete('/photo/{photo}', [
    'uses' => 'PhotoController@delete',
    'middleware' => ['can:delete,photo'],
]);


Route::post('user/authenticate', [
    'uses' => 'UserController@authenticate',
]);

Route::get('/user/{user}', [
    'uses' => 'UserController@view',
    'middleware' => ['can:view,user'],
]);

Route::post('/user', [
    'uses' => 'UserController@create',
    'middleware' => ['can:create,' . \App\Models\User::class],
]);

Route::put('/user/{user}', [
    'uses' => 'UserController@update',
    'middleware' => ['can:update,user'],
]);

Route::delete('/user/{user}', [
    'uses' => 'UserController@delete',
    'middleware' => ['can:delete,user'],
]);
