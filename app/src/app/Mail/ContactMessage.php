<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/**
 * Class ContactMessage.
 *
 * @package App\Mail
 */
class ContactMessage extends Mailable
{
    /**
     * @var array
     */
    public $data;

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
            ->view('app.mails.en.contact-message', ['data' => $this->data]);
    }
}
