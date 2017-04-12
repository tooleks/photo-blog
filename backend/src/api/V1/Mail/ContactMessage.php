<?php

namespace Api\V1\Mail;

use Illuminate\Mail\Mailable;

/**
 * Class ContactMessage.
 *
 * @property array data
 * @package Api\V1\Mail
 */
class ContactMessage extends Mailable
{
    /**
     * ContactMessage constructor.
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
            ->to(config('mail.address.administrator'))
            ->replyTo($this->data['email'])
            ->subject(sprintf('%s - %s - %s', config('app.name'), trans('mails.contact-message.subject'), $this->data['subject']))
            ->view('api.v1.mails.contact-message', $this->data);
    }
}
