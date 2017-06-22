<?php

namespace Core\Services\Rss\Contracts;

use Core\Services\Contracts\Runnable;
use Lib\Rss\Contracts\Builder;

/**
 * Interface RssBuilderService.
 *
 * @package Core\Services\Rss\Contracts
 */
interface RssBuilderService extends Runnable
{
    /**
     * Build the RSS feed.
     *
     * @param array ...$parameters
     * @return Builder
     */
    public function run(...$parameters): Builder;
}
