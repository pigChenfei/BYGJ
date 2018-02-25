<?php

namespace App\Repositories\Carrier;

use App\Models\Log\AgentAccountAdjustLog;
use InfyOm\Generator\Common\BaseRepository;

class AgentAccountAdjustLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'agent_id',
        'carrier_id',
        'adjust_type',
        'operator',
        'amount',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AgentAccountAdjustLog::class;
    }
}
