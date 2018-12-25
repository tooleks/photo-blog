<?php

namespace App\Managers\Subscription;

use App\Models\Builders\SubscriptionBuilder;
use App\Models\Subscription;
use Core\Contracts\SubscriptionManager;
use Core\Entities\SubscriptionEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionInterface as Database;
use function App\Util\str_unique;

/**
 * Class ARSubscriptionManager.
 *
 * @package App\Managers\Subscription
 */
class ARSubscriptionManager implements SubscriptionManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var SubscriptionValidator
     */
    private $validator;

    /**
     * ARSubscriptionManager constructor.
     *
     * @param Database $database
     * @param SubscriptionValidator $validator
     */
    public function __construct(Database $database, SubscriptionValidator $validator)
    {
        $this->database = $database;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): SubscriptionEntity
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $subscription = (new Subscription)->fill($attributes);

        $subscription->token = str_unique(64);

        $subscription->save();

        return $subscription->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getByToken(string $token): SubscriptionEntity
    {
        /** @var Subscription $subscription */
        $subscription = (new Subscription)
            ->newQuery()
            ->whereTokenEquals($token)
            ->firstOrFail();

        return $subscription->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator
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

        $paginator->getCollection()->transform(function (Subscription $subscription) {
            return $subscription->toEntity();
        });

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function deleteByToken(string $token): SubscriptionEntity
    {
        /** @var Subscription $subscription */
        $subscription = (new Subscription)
            ->newQuery()
            ->whereTokenEquals($token)
            ->firstOrFail();

        $subscription->delete();

        return $subscription->toEntity();
    }
}
