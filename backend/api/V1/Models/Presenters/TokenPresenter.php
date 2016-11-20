<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\User;
use Exception;

/**
 * Class TokenPresenter
 * @package Api\V1\Models\Presenters
 */
class TokenPresenter extends EntityPresenter
{
    /**
     * TokenPresenter constructor.
     *
     * @param User $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof User)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
    {
        return [
            'user_id' => 'id',
            'api_token' => 'api_token',
        ];
    }
}
