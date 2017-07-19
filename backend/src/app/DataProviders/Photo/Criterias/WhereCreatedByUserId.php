<?php

namespace App\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereCreatedByUserId.
 *
 * @package App\DataProviders\Photo\Criterias
 */
class WhereCreatedByUserId implements Criteria
{
    /**
     * @var bool
     */
    private $createdByUserId;

    /**
     * WhereCreatedByUserId constructor.
     *
     * @param int $createdByUserId
     */
    public function __construct(int $createdByUserId)
    {
        $this->createdByUserId = $createdByUserId;
    }

    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query->where('photos.created_by_user_id', $this->createdByUserId);
    }
}
