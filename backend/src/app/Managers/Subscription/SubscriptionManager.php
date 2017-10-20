<?php

namespace App\Managers\Subscription;

use App\Models\Builders\SubscriptionBuilder;
use function App\Util\str_unique;
use Closure;
use App\Managers\Subscription\Contracts\SubscriptionManager as SubscriptionManagerContract;
use App\Models\Subscription;
use Illuminate\Database\ConnectionInterface as DbConnection;
use Illuminate\Support\Collection;

/**
 * Class SubscriptionManager.
 *
 * @package App\Managers\Subscription
 */
class SubscriptionManager implements SubscriptionManagerContract
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * @var SubscriptionValidator
     */
    private $validator;

    /**
     * SubscriptionManager constructor.
     *
     * @param DbConnection $dbConnection
     * @param SubscriptionValidator $validator
     */
    public function __construct(DbConnection $dbConnection, SubscriptionValidator $validator)
    {
        $this->dbConnection = $dbConnection;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function getByToken(string $token): Subscription
    {
        $subscription = (new Subscription)
            ->newQuery()
            ->whereTokenEquals($token)
            ->firstOrFail();

        return $subscription;
    }

    /**
     * @inheritdoc
     */
    public function eachFilteredByEmails(Closure $callback, array $emails): void
    {
        (new Subscription)
            ->newQuery()
            ->whereEmailIn($emails)
            ->chunk(10, function (Collection $collection) use ($callback) {
                $collection->each($callback);
            });
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): Subscription
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $subscription = (new Subscription)->fill($attributes);

        $subscription->token = str_unique(64);

        $subscription->save();

        return $subscription;
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page, int $perPage, array $filters = [])
    {
        $this->validator->validateForPaginate($filters);

        $sortAttribute = $filters['sort_attribute'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $query = (new Subscription)
            ->newQuery()
            ->when(isset($filters['id']), function (SubscriptionBuilder $query) use ($filters) {
                return $query->whereIds($filters['id']);
            })
            ->when(isset($filters['email']), function (SubscriptionBuilder $query) use ($filters) {
                return $query->whereEmailLike($filters['email'] . '%');
            })
            ->when(isset($filters['token']), function (SubscriptionBuilder $query) use ($filters) {
                return $query->whereTokenEquals($filters['token']);
            })
            ->orderBy($sortAttribute, $sortOrder);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function delete(Subscription $subscription): void
    {
        $subscription->delete();
    }
}
