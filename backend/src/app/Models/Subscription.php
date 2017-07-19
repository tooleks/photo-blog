<?php

namespace App\Models;

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
}
