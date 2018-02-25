<?php

namespace App\Repositories\Web;

use App\Models\Log\PlayerDepositPayLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerDepositPayLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pay_order_number',
        'carrier_id',
        'pay_order_channel_trade_number',
        'player_id',
        'amount',
        'finally_amount',
        'benefit_amount',
        'bonus_amount',
        'withdraw_flow_limit_amount',
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
        return PlayerDepositPayLog::class;
    }
}
