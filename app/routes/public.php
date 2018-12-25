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
    ->uses(\App\Http\Actions\SiteMapGetAction::class);

Route::get('rss.xml')
    ->name('rss')
    ->uses(\App\Http\Actions\RssGetAction::class);

Route::get('manifest.json')
    ->name('manifest')
    ->uses(\App\Http\Actions\ManifestGetAction::class);

Route::get('{any}')
    ->where('any', '.*')
    ->uses(\App\Http\Actions\IndexGetAction::class);
