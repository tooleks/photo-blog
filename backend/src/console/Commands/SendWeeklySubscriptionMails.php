<?php

namespace Console\Commands;

use Api\V1\Mail\WeeklySubscription;
use Carbon\Carbon;
use Closure;
use Core\Models\Photo;
use Core\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SendWeeklySubscriptionMails.
 *
 * @property Mailer mailer
 * @package Console\Commands
 */
class SendWeeklySubscriptionMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly_subscription_mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly subscription mails';

    /**
     * SendWeeklySubscriptionMails constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        parent::__construct();

        $this->mailer = $mailer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->isWeeklySubscriptionAvailable()) {
            $this->eachSubscription(function (Subscription $subscription) {
                $this->comment(sprintf('Sending mail to subscription (email:%s) ...', $subscription->email));
                $this->sendWeeklySubscriptionMail($subscription);
            });
        } else {
            $this->comment('There are no available weekly updates.');
        }
    }

    /**
     * Determine if weekly subscription is available.
     *
     * @return bool
     */
    protected function isWeeklySubscriptionAvailable() : bool
    {
        return Photo::whereIsPublished(true)->where('created_at', '>', (new Carbon())->addWeek('-1'))->exists();
    }

    /**
     * Apply callback function on each subscription in database.
     *
     * @param Closure $callback
     * @return void
     */
    protected function eachSubscription(Closure $callback)
    {
        Subscription::chunk(100, function (Collection $photos) use ($callback) {
            $photos->map($callback);
        });
    }

    /**
     * Send weekly subscription mail.
     *
     * @param Subscription $subscription
     * @return void
     */
    protected function sendWeeklySubscriptionMail(Subscription $subscription)
    {
        $data = $this->extendSubscriptionData($subscription->toArray());

        $this->mailer->send(new WeeklySubscription($data));
    }

    /**
     * Prepare subscription data.
     *
     * @param array $data
     * @return array
     */
    protected function extendSubscriptionData($data) : array
    {
        $data['website_url'] = config('main.frontend.url');
        $data['unsubscribe_url'] = config('main.frontend.unsubscribe_url') . '/' . $data['token'];

        return $data;
    }
}
