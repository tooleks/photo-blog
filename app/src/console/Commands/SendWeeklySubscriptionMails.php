<?php

namespace Console\Commands;

use App\Mail\WeeklySubscription;
use App\Models\Builders\SubscriptionBuilder;
use App\Models\Post;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use function App\Util\url_frontend;
use function App\Util\url_frontend_unsubscription;

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
    protected $signature = 'send:weekly-subscription-mails
                                {--chunk_size=50}
                                {--email=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly subscription mails';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $postsExists = (new Post)
            ->newQuery()
            ->whereIsPublished()
            ->wherePublishedAtGreaterThanOrEqualTo(Carbon::now()->addWeek(-1))
            ->exists();

        if ($postsExists) {
            (new Subscription)
                ->newQuery()
                ->when($this->option('email'), function (SubscriptionBuilder $query) {
                    return $query->whereEmailIn($this->option('email'));
                })
                ->chunk($this->option('chunk_size'), function (Collection $subscriptions) {
                    $subscriptions->each(function (Subscription $subscription) {
                        $this->comment("Queuing subscription mail to {$subscription->email}...");
                        Mail::queue(new WeeklySubscription([
                            'website_url' => url_frontend(),
                            'subscriber_email' => $subscription->email,
                            'unsubscribe_url' => url_frontend_unsubscription($subscription->token),
                        ]));
                    });
                });
        }
    }
}
