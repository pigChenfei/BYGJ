<?php

namespace App\Repositories\Carrier;

use App\Models\Log\CarrierAgentDepositPayLog;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentDepositPayLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pay_order_number',
        'carrier_id',
        'pay_order_channel_trade_number',
        'agent_id',
        'amount',
        'finally_amount',
        'benefit_amount',
        'bonus_amount',
        'fee_amount',
        'pay_channel',
        'status',
        'review_user_id',
        'operate_time',
        'credential',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentDepositPayLog::class;
    }
}
