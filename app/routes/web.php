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
    ->uses('SiteMapController@xml');

Route::get('rss.xml')
    ->name('rss')
    ->uses('RssController@xml');

Route::get('manifest.json')
    ->name('manifest')
    ->uses('ManifestController@json');

Route::get('{any}')
    ->where('any', '.*')
    ->uses('IndexController@index');
