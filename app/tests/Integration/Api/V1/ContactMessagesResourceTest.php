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
    public function validCreateAttributesProvider(): array
    {
        return [
            [[
                'email' => 'john.doe@domain.name',
                'name' => 'John Doe',
                'subject' => 'The message subject',
                'message' => 'The message text.',
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
                'message' => 'The message text.',
            ]],
            [[
                'name' => 'John Doe',
                'subject' => 'The message subject',
                'message' => 'The message text.',
            ]],
            [[
                'email' => 'john.doe_domain.name',
                'subject' => 'The message subject',
                'message' => 'The message text.',
            ]],
            [[
                'email' => 'john.doe_domain.name',
                'name' => 'John Doe',
                'message' => 'The message text.',
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
                $mail->data['message'] === $requestBody['message'];
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

    protected function getResourceName(): string
    {
        return 'contact_messages';
    }
}
