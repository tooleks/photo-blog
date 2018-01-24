<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Subscription;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class SubscriptionPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class SubscriptionPlainResource extends Resource
{
    /**
     * @var Subscription
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'email' => CastValue::toStringOrNull(html_purify($this->resource->email)),
            'token' => CastValue::toStringOrNull(html_purify($this->resource->token)),
        ];
    }
}
