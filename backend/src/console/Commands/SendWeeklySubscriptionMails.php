<?php

namespace Console\Commands;

use Api\V1\Mail\WeeklySubscription;
use Carbon\Carbon;
use Closure;
use Core\Models\Photo;
use Core\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendWeeklySubscriptionMails.
 *
 * @package Console\Commands
 */
class SendWeeklySubscriptionMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly_subscription_mails {email_filter?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly subscription mails';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->isAvailableWeeklySubscription()) {
            $this->eachSubscriptionByEmailFilter(function (Subscription $subscription) {
                $this->sendWeeklySubscriptionMail($subscription);
            });
        }
    }

    /**
     * Determine if weekly subscription is available.
     *
     * @return bool
     */
    protected function isAvailableWeeklySubscription() : bool
    {
        $query = (new Photo)->newQuery();

        return $query
            ->whereIsPublished(true)
            ->where('created_at', '>', (new Carbon)->addWeek('-1'))
            ->exists();
    }

    /**
     * Apply callback function on each subscription in database.
     *
     * @param Closure $callback
     * @return void
     */
    protected function eachSubscriptionByEmailFilter(Closure $callback)
    {
        $query = (new Subscription)->newQuery();

        if ($this->argument('email_filter')) {
            $query->whereIn('email', $this->argument('email_filter'));
        }

        $query->chunk(100, function (Collection $subscription) use ($callback) {
            $subscription->each($callback);
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
        $this->comment(sprintf('Sending mail to subscription (email:%s) ...', $subscription->email));

        $data = $this->extractSubscriptionData($subscription);

        Mail::send(new WeeklySubscription($data));
    }

    /**
     * Extract subscription data.
     *
     * @param Subscription $subscription
     * @return array
     */
    protected function extractSubscriptionData(Subscription $subscription) : array
    {
        $data = $subscription->toArray();

        $data['website_url'] = config('main.frontend.url');
        $data['unsubscribe_url'] = sprintf('%s/%s', config('main.frontend.unsubscribe_url'), $data['token']);

        return $data;
    }
}
