<?php

/**
 * Class RssTest.
 */
class RssTest extends IntegrationAppTestCase
{
    public function testIndexSuccess()
    {
        $this
            ->get('/rss')
            ->assertStatus(200);
    }
}
