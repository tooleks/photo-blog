<?php

namespace App\Services\Manifest;

use App\Services\Manifest\Contracts\Manifest;
use Illuminate\Config\Repository as Config;

/**
 * Class AppManifest.
 *
 * @package App\Services\Rss
 */
class AppManifest implements Manifest
{
    /**
     * @var Config
     */
    private $config;

    /**
     * AppManifest constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function get(): array
    {
        return [
            'short_name' => $this->config->get('app.name'),
            'name' => $this->config->get('app.name'),
            'description' => $this->config->get('app.description'),
            'start_url' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#f3f3f4',
            'theme_color' => '#f3f3f4',
            'icons' => [
                [
                    'src' => (string) mix('/images/icon-72x72.png'),
                    'type' => 'image/png',
                    'sizes' => '72x72',
                ],
                [
                    'src' => (string) mix('/images/icon-96x96.png'),
                    'type' => 'image/png',
                    'sizes' => '96x96',
                ],
                [
                    'src' => (string) mix('/images/icon-128x128.png'),
                    'type' => 'image/png',
                    'sizes' => '128x128',
                ],
                [
                    'src' => (string) mix('/images/icon-144x144.png'),
                    'type' => 'image/png',
                    'sizes' => '144x144',
                ],
                [
                    'src' => (string) mix('/images/icon-152x152.png'),
                    'type' => 'image/png',
                    'sizes' => '152x152',
                ],
                [
                    'src' => (string) mix('/images/icon-192x192.png'),
                    'type' => 'image/png',
                    'sizes' => '192x192',
                ],
                [
                    'src' => (string) mix('/images/icon-384x384.png'),
                    'type' => 'image/png',
                    'sizes' => '384x384',
                ],
                [
                    'src' => (string) mix('/images/icon-512x512.png'),
                    'type' => 'image/png',
                    'sizes' => '512x512',
                ],
            ],
        ];
    }
}
