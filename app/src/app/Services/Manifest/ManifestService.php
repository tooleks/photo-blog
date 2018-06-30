<?php

namespace App\Services\Manifest;

use App\Services\Manifest\Contracts\ManifestService as ManifestServiceContract;
use Illuminate\Config\Repository as Config;

/**
 * Class ManifestService.
 *
 * @package App\Services\Rss
 */
class ManifestService implements ManifestServiceContract
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ManifestService constructor.
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
                    'src' => (string) mix('/favicon/favicon-16x16.png'),
                    'type' => 'image/png',
                    'sizes' => '16x16',
                ],
                [
                    'src' => (string) mix('/favicon/favicon-32x32.png'),
                    'type' => 'image/png',
                    'sizes' => '32x32',
                ],
                [
                    'src' => (string) mix('/favicon/favicon-96x96.png'),
                    'type' => 'image/png',
                    'sizes' => '96x96',
                ],
                [
                    'src' => (string) mix('/favicon/favicon-original.png'),
                    'type' => 'image/png',
                    'sizes' => '128x128',
                ],
            ],
        ];
    }
}
