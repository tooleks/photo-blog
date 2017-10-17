<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\User;
use App\Util\Normalizer;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class UserPlainResource extends Resource
{
    use Normalizer;

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
            'id' => $this->normalizeInt(html_purify($this->resource->id)),
            'name' => $this->normalizeString(html_purify($this->resource->name)),
            'email' => $this->when($isVisibleUserContacts, function () {
                return $this->normalizeString(html_purify($this->resource->email));
            }),
            'role' => $this->normalizeString(html_purify($this->resource->role->name)),
            'created_at' => $this->normalizeString(html_purify($this->resource->created_at)),
            'updated_at' => $this->normalizeString(html_purify($this->resource->updated_at)),
        ];
    }
}
