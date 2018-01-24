<?php

namespace Tests\Integration\App;

/**
 * Class SiteMapTest.
 *
 * @package Tests\Integration\App
 */
class SiteMapTest extends TestCase
{
    public function testIndexSuccess(): void
    {
        $this
            ->get('/sitemap.xml')
            ->assertStatus(200);
    }
}
