<?php

use Api\V1\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactMessagesResourceTest.
 */
class ContactMessagesResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'contact_messages';

    protected $resourceStructure = [];

    public function testCreateSuccess()
    {
        Mail::fake();

        $this
            ->json('POST', sprintf('/%s', $this->resourceName), $body = [
                'email' => $this->fake()->email,
                'name' => $this->fake()->name,
                'subject' => $this->fake()->text(50),
                'text' => $this->fake()->text(200),
            ])
            ->assertStatus(201)
            ->assertJsonStructure($this->resourceStructure);

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) use ($body) {
            return
                $mail->data['email'] === $body['email'] &&
                $mail->data['name'] === $body['name'] &&
                $mail->data['subject'] === $body['subject'] &&
                $mail->data['text'] === $body['text'];
        });
    }
}
