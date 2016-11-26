<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\Tag;
use Exception;

/**
 * Class TagPresenter
 * @property Tag entity
 * @package Api\V1\Models\Presenters
 */
class TagPresenter extends EntityPresenter
{
    /**
     * RolePresenter constructor.
     *
     * @param Tag $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof Tag)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
    {
        return [
            'text' => 'text',
        ];
    }
}
