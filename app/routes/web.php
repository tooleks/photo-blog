<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::options('{any}')
    ->where('any', '.*');

Route::get('sitemap.xml')
    ->name('sitemap')
    ->uses('SiteMapController@index');

Route::get('rss.xml')
    ->name('rss')
    ->uses('RssController@index');

Route::get('{any}')
    ->where('any', '.*')
    ->uses('IndexController@index');
