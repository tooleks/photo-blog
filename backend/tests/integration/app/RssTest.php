<?php

/**
 * Class RssTest.
 */
class RssTest extends IntegrationAppTestCase
{
    public function testIndexSuccess()
    {
        $this
            ->get('/rss.xml')
            ->assertStatus(200);
    }
}
