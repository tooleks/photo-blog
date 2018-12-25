<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPackageServices();

        $this->registerLibServices();

        $this->registerAppServices();

        $this->registerApiV1Services();
    }

    /**
     * Register "package" services.
     *
     * @return void
     */
    protected function registerPackageServices(): void
    {
        $this->app->bind(
            \GuzzleHttp\ClientInterface::class,
            \GuzzleHttp\Client::class
        );

        $this->app->bind(
            \Imagine\Image\ImagineInterface::class,
            \Imagine\Imagick\Imagine::class
        );

        $this->app->singleton('HTMLPurifier', function (Application $app) {
            $filesystem = $app->make('filesystem')->disk('local');
            $cacheDirectory = 'cache/HTMLPurifier_DefinitionCache';
            if (!$filesystem->exists($cacheDirectory)) {
                $filesystem->makeDirectory($cacheDirectory);
            }
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('Cache.SerializerPath', storage_path("app/{$cacheDirectory}"));
            return new \HTMLPurifier($config);
        });
    }

    /**
     * Register "lib" services.
     *
     * @return void
     */
    protected function registerLibServices(): void
    {
        $this->app->bind(
            \Lib\Rss\Contracts\Builder::class,
            \Lib\Rss\Builder::class
        );

        $this->app->bind(
            \Lib\SiteMap\Contracts\Builder::class,
            \Lib\SiteMap\Builder::class
        );
    }

    /**
     * Register "app" services.
     *
     * @return void
     */
    protected function registerAppServices(): void
    {
        $this->app->bind(
            \Core\Contracts\LocationManager::class,
            \App\Managers\Location\ARLocationManager::class
        );

        $this->app->bind(
            \Core\Contracts\PostManager::class,
            \App\Managers\Post\ARPostManager::class
        );

        $this->app->bind(
            \Core\Contracts\PhotoManager::class,
            \App\Managers\Photo\ARPhotoManager::class
        );

        $this->app->bind(
            \Core\Contracts\SubscriptionManager::class,
            \App\Managers\Subscription\ARSubscriptionManager::class
        );

        $this->app->bind(
            \Core\Contracts\TagManager::class,
            \App\Managers\Tag\ARTagManager::class
        );

        $this->app->bind(
            \Core\Contracts\UserManager::class,
            \App\Managers\User\ARUserManager::class
        );

        $this->app->bind(
            \App\Services\Image\Contracts\ImageProcessor::class,
            \App\Services\Image\ImagineImageProcessor::class
        );

        $this->app->when(\App\Services\Image\ImagineImageProcessor::class)
            ->needs('$config')
            ->give(function (Application $app) {
                return [
                    'thumbnails' => $app->make('config')->get('main.photo.thumbnails'),
                ];
            });

        $this->app->bind(
            \App\Services\Manifest\Contracts\Manifest::class,
            \App\Services\Manifest\AppManifest::class
        );

        $this->app->bind(
            \App\Services\SiteMap\Contracts\SiteMapBuilder::class,
            \App\Services\SiteMap\AppSiteMapBuilder::class
        );

        $this->app->bind(
            \App\Services\Rss\Contracts\RssBuilder::class,
            \App\Services\Rss\AppRssBuilder::class
        );

        $this->app->bind(
            \App\Rules\ReCaptchaRule::class,
            function () {
                return new \App\Rules\ReCaptchaRule(env('GOOGLE_RECAPTCHA_SECRET_KEY'));
            }
        );
    }

    /**
     * Register "api.v1" services.
     *
     * @return void
     */
    protected function registerApiV1Services(): void
    {
        $this->app->bind(
            \Api\V1\Http\Proxy\Contracts\OAuthProxy::class,
            \Api\V1\Http\Proxy\CookieOAuthProxy::class
        );
    }
}
