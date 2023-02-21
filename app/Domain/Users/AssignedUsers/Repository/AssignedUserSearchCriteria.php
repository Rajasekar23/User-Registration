<?php

namespace App\Domain\Users\AssignedUsers\Repository;

use App\Core\CoreQueryBuilder;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AssignedUserSearchCriteria implements CriteriaInterface
{
    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Generic Criteria.
     *
     * @param CoreQueryBuilder|CoreModel $model      -
     * @param RepositoryInterface        $repository -
     *
     * @return CoreQueryBuilder
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model
            ->with(['user', 'assignedTo'])
        ;
    }
}
