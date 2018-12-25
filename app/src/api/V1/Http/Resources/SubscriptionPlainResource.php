<?php

namespace Api\V1\Http\Resources;

use Core\Entities\SubscriptionEntity;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_string;

/**
 * Class SubscriptionPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class SubscriptionPlainResource extends Resource
{
    /**
     * @var SubscriptionEntity
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'email' => to_string(html_purify($this->resource->getEmail())),
            'token' => to_string(html_purify($this->resource->getToken())),
        ];
    }
}
