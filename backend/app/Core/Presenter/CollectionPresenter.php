<?php

namespace App\Core\Presenter;

use Illuminate\Support\Collection;

/**
 * Class CollectionPresenter
 * @package App\Presenters
 */
abstract class CollectionPresenter extends Collection
{
    /**
     * Entity presenter full class name.
     *
     * Example: \App\Presenters\SomeEntityPresenter::class
     *
     * @var string
     */
    protected $entityPresenterClassName;

    /**
     * CollectionPresenter constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct([]);

        $collection->map(function ($item) {
            $this->push(new $this->entityPresenterClassName($item));
        });
    }
}
