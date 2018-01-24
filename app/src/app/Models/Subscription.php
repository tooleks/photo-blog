<?php

namespace App\Models;

use App\Models\Builders\SubscriptionBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription.
 *
 * @property int id
 * @property string email
 * @property string token
 * @package App\Models
 */
class Subscription extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'email',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): SubscriptionBuilder
    {
        return new SubscriptionBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): SubscriptionBuilder
    {
        return parent::newQuery();
    }
}
