<?php

namespace Tests\Integration\App;

/**
 * Class RssTest.
 *
 * @package Tests\Integration\App
 */
class RssTest extends TestCase
{
    public function testIndexSuccess(): void
    {
        $this
            ->get('/rss.xml')
            ->assertStatus(200);
    }
}
