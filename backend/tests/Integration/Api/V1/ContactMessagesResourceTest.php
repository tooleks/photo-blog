<?php

namespace Tests\Integration\Api\V1;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactMessagesResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class ContactMessagesResourceTest extends TestCase
{
    protected function getResourceName(): string
    {
        return 'contact_messages';
    }

    public function validCreateAttributesProvider(): array
    {
        return [
            [[
                'email' => 'john.doe@domain.name',
                'name' => 'John Doe',
                'subject' => 'The message subject',
                'text' => 'The message text.',
            ]],
        ];
    }

    public function invalidCreateAttributesProvider(): array
    {
        return [
            [[]],
            [[
                'email' => 'john.doe_domain.name',
                'name' => 'John Doe',
                'subject' => 'The message subject',
                'text' => 'The message text.',
            ]],
            [[
                'name' => 'John Doe',
                'subject' => 'The message subject',
                'text' => 'The message text.',
            ]],
            [[
                'email' => 'john.doe_domain.name',
                'subject' => 'The message subject',
                'text' => 'The message text.',
            ]],
            [[
                'email' => 'john.doe_domain.name',
                'name' => 'John Doe',
                'text' => 'The message text.',
            ]],
            [[
                'email' => 'john.doe_domain.name',
                'name' => 'John Doe',
                'subject' => 'The message subject',
            ]],
        ];
    }

    /**
     * @dataProvider validCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateSuccess(array $requestBody): void
    {
        Mail::fake();

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(204);

        Mail::assertQueued(ContactMessage::class, function (ContactMessage $mail) use ($requestBody) {
            return
                $mail->data['email'] === $requestBody['email'] &&
                $mail->data['name'] === $requestBody['name'] &&
                $mail->data['subject'] === $requestBody['subject'] &&
                $mail->data['text'] === $requestBody['text'];
        });
    }

    /**
     * @dataProvider invalidCreateAttributesProvider
     * @param array $requestBody
     * @return void
     */
    public function testCreateValidationFail(array $requestBody): void
    {
        Mail::fake();

        $this
            ->json('POST', $this->getResourceFullName(), $requestBody)
            ->assertStatus(422);
    }
}
