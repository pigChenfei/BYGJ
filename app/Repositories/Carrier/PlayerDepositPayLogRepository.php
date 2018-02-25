<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerDepositPayLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerDepositPayLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'player_id',
        'amount',
        'finally_amount',
        'benefit_amount',
        'bonus_amount',
        'withdraw_flow_limit_amount',
        'pay_channel',
        'status',
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
