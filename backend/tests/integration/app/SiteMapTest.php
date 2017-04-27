<?php

/**
 * Class SiteMapTest.
 */
class SiteMapTest extends IntegrationAppTestCase
{
    public function testIndex()
    {
        $this
            ->get('/sitemap.xml')
            ->assertStatus(200);
    }
}
