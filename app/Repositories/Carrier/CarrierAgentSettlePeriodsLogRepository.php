<?php

namespace App\Repositories\Carrier;

use App\Models\Carrier\CarrierCommissionSettlePeriodsLog;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentSettlePeriodsLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'agent_id',
        'periods',
        'settle_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierSettlePeriodsLog::class;
    }
}
