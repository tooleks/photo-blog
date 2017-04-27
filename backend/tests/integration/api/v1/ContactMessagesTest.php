<?php

/**
 * Class ContactMessagesTest.
 */
class ContactMessagesTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'contact_messages';

    public function testPostValidation()
    {
        $this
            ->json('POST', sprintf('/%s', $this->resourceName))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'name',
                    'subject',
                    'text',
                ],
            ]);

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => 'invalid_email',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'name',
                    'subject',
                    'text',
                ],
            ]);
    }

    public function testPost()
    {
        $this
            ->json('POST', sprintf('/%s', $this->resourceName), [
                'email' => $this->fake()->email,
                'name' => $this->fake()->name,
                'subject' => $this->fake()->text(50),
                'text' => $this->fake()->text(200),
            ])
            ->assertStatus(201)
            ->assertJsonStructure([]);
    }
}
