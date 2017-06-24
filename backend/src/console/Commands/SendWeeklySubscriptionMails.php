<?php

namespace Console\Commands;

use Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider;
use Core\DataProviders\Subscription\Criterias\WhereEmailIn;
use Core\Mail\WeeklySubscription;
use Core\Managers\Photo\Contracts\PhotoManager;
use Core\Models\Subscription;
use Closure;
use Illuminate\Config\Repository as Config;
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
     * @var Config
     */
    private $config;

    /**
     * @var SubscriptionDataProvider
     */
    private $subscriptionDataProvider;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * SendWeeklySubscriptionMails constructor.
     *
     * @param Config $config
     * @param SubscriptionDataProvider $subscriptionDataProvider
     * @param PhotoManager $photoManager
     */
    public function __construct(Config $config, SubscriptionDataProvider $subscriptionDataProvider, PhotoManager $photoManager)
    {
        parent::__construct();

        $this->config = $config;
        $this->subscriptionDataProvider = $subscriptionDataProvider;
        $this->photoManager = $photoManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->photoManager->existsPublishedOlderThanWeek()) {
            $this->eachSubscriptionByEmailFilterArgument(function (Subscription $subscription) {
                $this->comment("Sending subscription mail [recipient:{$subscription->email}] ...");
                $this->sendMail($subscription);
            });
        }
    }

    /**
     *
     *
     * @param Closure $closure
     */
    protected function eachSubscriptionByEmailFilterArgument(Closure $closure)
    {
        $this->subscriptionDataProvider
            // Note: The $this->hasArgument('email_filter') method call not working properly.
            ->applyCriteriaWhen((bool) $this->argument('email_filter'), new WhereEmailIn($this->argument('email_filter')))
            ->each($closure);
    }

    /**
     * Send mail.
     *
     * @param Subscription $subscription
     */
    protected function sendMail(Subscription $subscription)
    {
        $data['website_url'] = $this->config->get('main.frontend.url');

        $data['subscriber_email'] = $subscription->email;

        $data['unsubscribe_url'] = sprintf($this->config->get('format.frontend.url.unsubscription_page'), $subscription->token);

        Mail::send(new WeeklySubscription($data));
    }
}
