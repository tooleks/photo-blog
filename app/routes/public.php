<?php

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
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
