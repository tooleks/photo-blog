<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\User;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class UserPlainResource extends Resource
{
    /**
     * @var User
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        $isVisibleUserContacts = optional($request->user())->can('get-user-contacts', $this->resource);

        return [
            'id' => CastValue::toIntOrNull(html_purify($this->resource->id)),
            'name' => CastValue::toStringOrNull(html_purify($this->resource->name)),
            'email' => $this->when($isVisibleUserContacts, function () {
                return CastValue::toStringOrNull(html_purify($this->resource->email));
            }),
            'role' => CastValue::toStringOrNull(html_purify($this->resource->role->name)),
            'created_at' => CastValue::toStringOrNull(html_purify($this->resource->created_at)),
            'updated_at' => CastValue::toStringOrNull(html_purify($this->resource->updated_at)),
        ];
    }
}
