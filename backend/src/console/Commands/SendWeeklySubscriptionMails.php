<?php

namespace Console\Commands;

use function App\Util\url_frontend;
use function App\Util\url_frontend_unsubscription;
use App\Mail\WeeklySubscription;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Managers\Subscription\Contracts\SubscriptionManager;
use App\Models\Subscription;
use Illuminate\Console\Command;
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
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * SendWeeklySubscriptionMails constructor.
     *
     * @param SubscriptionManager $subscriptionManager
     * @param PhotoManager $photoManager
     */
    public function __construct(SubscriptionManager $subscriptionManager, PhotoManager $photoManager)
    {
        parent::__construct();

        $this->subscriptionManager = $subscriptionManager;
        $this->photoManager = $photoManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->photoManager->existsPublishedLessThanWeekAgo()) {
            $this->subscriptionManager->eachFilteredByEmails(function (Subscription $subscription) {
                $this->comment("Sending subscription mail {$subscription->email}.");
                $this->sendMail($subscription);
            }, $this->argument('email_filter'));
        }
    }

    /**
     * Send mail.
     *
     * @param Subscription $subscription
     */
    protected function sendMail(Subscription $subscription)
    {
        $data['website_url'] = url_frontend();

        $data['subscriber_email'] = $subscription->email;

        $data['unsubscribe_url'] = url_frontend_unsubscription($subscription->token);

        Mail::queue(new WeeklySubscription($data));
    }
}
