<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription.
 *
 * @property int id
 * @property string email
 * @property string token
 * @package Core\Models
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
}
