<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\User;
use App\Util\CastsValues;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class UserPlainResource extends Resource
{
    use CastsValues;

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
            'id' => $this->toIntOrNull(html_purify($this->resource->id)),
            'name' => $this->toStringOrNull(html_purify($this->resource->name)),
            'email' => $this->when($isVisibleUserContacts, function () {
                return $this->toStringOrNull(html_purify($this->resource->email));
            }),
            'role' => $this->toStringOrNull(html_purify($this->resource->role->name)),
            'created_at' => $this->toStringOrNull(html_purify($this->resource->created_at)),
            'updated_at' => $this->toStringOrNull(html_purify($this->resource->updated_at)),
        ];
    }
}
