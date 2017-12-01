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
    private $wrapper;

    /**
     * PaginatedResource constructor.
     *
     * @param mixed $resource
     * @param string|callable $wrapper
     */
    public function __construct($resource, $wrapper)
    {
        parent::__construct($resource);

        $this->wrapper = $wrapper;
    }

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        $wrapper = $this->wrapper;
        if (is_callable($wrapper)) {
            $data = $wrapper($this->collection);
        } elseif (class_exists($wrapper)) {
            $data = $this->collection->map(function ($item) use ($wrapper) {
                return new $wrapper($item);
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
