<?php

namespace Api\V1\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class PaginatedResource.
 *
 * @package Api\V1\Http\Resources
 */
class PaginatedResource extends ResourceCollection
{
    /**
     * @var string|callable
     */
    private $resourceModifier;

    /**
     * PaginatedResource constructor.
     *
     * @param mixed $resource
     * @param string|callable $resourceModifier
     */
    public function __construct($resource, $resourceModifier)
    {
        parent::__construct($resource);

        $this->resourceModifier = $resourceModifier;
    }

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        $resourceModifier = $this->resourceModifier;

        if (is_callable($resourceModifier)) {
            $data = $resourceModifier($this->collection);
        } elseif (class_exists($resourceModifier)) {
            $data = $this->collection->map(function ($item) use ($resourceModifier) {
                return new $resourceModifier($item);
            });
        }

        return [
            'data' => $data ?? [],
            'first_page_url' => $this->resource->url(1),
            'last_page_url' => $this->resource->url($this->resource->lastPage()),
            'next_page_url' => $this->resource->nextPageUrl(),
            'prev_page_url' => $this->resource->previousPageUrl(),
            'current_page' => $this->resource->currentPage(),
            'last_page' => $this->resource->lastPage(),
            'per_page' => $this->resource->perPage(),
            'from' => $this->resource->firstItem(),
            'to' => $this->resource->lastItem(),
            'total' => $this->resource->total(),
        ];
    }
}
