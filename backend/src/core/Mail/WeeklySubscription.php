<?php

namespace Core\Mail;

use Illuminate\Mail\Mailable;

/**
 * Class WeeklySubscription.
 *
 * @property array data
 * @package Core\Mail
 */
class WeeklySubscription extends Mailable
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
            ->to($this->data['subscriber_email'])
            ->subject(sprintf('%s - %s', config('app.name'), trans('mails.weekly-subscription.subject')))
            ->view('core.mails.weekly-subscription', $this->data);
    }
}
