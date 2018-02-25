<?php

namespace App\Criteria\Agent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CarrierPlayerCriteria
 * @package namespace App\Criteria;
 */
class AgentUserSelectCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('agent_id',\Auth::user()->id);
        return $model;
    }
}
