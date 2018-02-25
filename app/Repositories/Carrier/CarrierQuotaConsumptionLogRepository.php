<?php

namespace App\Repositories\Carrier;

use App\Models\Log\CarrierQuotaConsumptionLog;
use InfyOm\Generator\Common\BaseRepository;

class CarrierQuotaConsumptionLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'amount',
        'pay_channel_remain_amount',
        'related_pay_channel',
        'consumption_source',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierQuotaConsumptionLog::class;
    }
}
