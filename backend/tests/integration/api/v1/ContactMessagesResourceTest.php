<?php

/**
 * Class ContactMessagesResourceTest.
 */
class ContactMessagesResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'contact_messages';

    protected $resourceStructure = [];

    public function testCreateSuccess()
    {
        $response = $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $this->fake()->email,
                'name' => $this->fake()->name,
                'subject' => $this->fake()->text(50),
                'text' => $this->fake()->text(200),
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure);
    }
}
