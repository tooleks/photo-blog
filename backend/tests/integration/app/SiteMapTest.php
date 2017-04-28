<?php

/**
 * Class SiteMapTest.
 */
class SiteMapTest extends IntegrationAppTestCase
{
    public function testIndexSuccess()
    {
        $this
            ->get('/sitemap.xml')
            ->assertStatus(200);
    }
}
