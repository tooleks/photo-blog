<?php

namespace Console\Commands;

use Api\V1\Mail\WeeklySubscription;
use Carbon\Carbon;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider;
use Core\DataProviders\Subscription\Criterias\WhereEmailIn;
use Core\Models\Subscription;
use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Lib\DataProvider\Criterias\WhereCreatedAtGreaterThan;

/**
 * Class SendWeeklySubscriptionMails.
 *
 * @property Config config
 * @property SubscriptionDataProvider subscriptionDataProvider
 * @property PhotoDataProvider photoDataProvider
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
     * SendWeeklySubscriptionMails constructor.
     *
     * @param Config $config
     * @param SubscriptionDataProvider $subscriptionDataProvider
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Config $config, SubscriptionDataProvider $subscriptionDataProvider, PhotoDataProvider $photoDataProvider)
    {
        parent::__construct();

        $this->config = $config;
        $this->subscriptionDataProvider = $subscriptionDataProvider;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isAvailableWeeklySubscription()) {
            $this->eachSubscriptionByEmailFilterArgument(function (Subscription $subscription) {
                $this->comment("Sending subscription mail [recipient:{$subscription->email}] ...");
                $this->sendMail($subscription);
            });
        }
    }

    /**
     * Determine if weekly subscription is available.
     *
     * @return bool
     */
    protected function isAvailableWeeklySubscription(): bool
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria(new WhereCreatedAtGreaterThan((new Carbon)->addWeek('-1')))
            ->exists();
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
        $mail = new WeeklySubscription($this->extractSubscriptionData($subscription));

        Mail::send($mail);
    }

    /**
     * Extract subscription data.
     *
     * @param Subscription $subscription
     * @return array
     */
    protected function extractSubscriptionData(Subscription $subscription): array
    {
        $data = $subscription->toArray();

        $data['website_url'] = $this->config->get('main.frontend.url');
        $data['unsubscribe_url'] = sprintf('%s/%s', $this->config->get('main.frontend.unsubscribe_url'), $data['token']);

        return $data;
    }
}
