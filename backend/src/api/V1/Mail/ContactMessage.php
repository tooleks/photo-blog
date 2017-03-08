<?php

namespace Api\V1\Mail;

use Illuminate\Mail\Mailable;

/**
 * Class ContactEmail.
 *
 * @property array data
 * @package Api\V1\Mail
 */
class ContactMessage extends Mailable
{
    /**
     * ContactUs constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->replyTo($this->data['email'])
            ->subject(implode(' - ', [config('app.name'), trans('mails.contact-message.subject'), $this->data['subject']]))
            ->view('mails.contact-message', $this->data);
    }
}
