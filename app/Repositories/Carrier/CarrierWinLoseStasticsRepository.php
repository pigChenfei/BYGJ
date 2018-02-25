<?php

namespace App\Repositories\Carrier;

use App\Models\Log\CarrierWinLoseStastics;
use InfyOm\Generator\Common\BaseRepository;

class CarrierWinLoseStasticsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'register_count',
        'login_count',
        'deposit_amount',
        'first_deposit_amount',
        'deposit_count',
        'first_deposit_count',
        'withdraw_amount',
        'winlose_amount',
        'bonus_amount',
        'rebate_financial_flow_amount',
        'deposit_benefit_amount',
        'carrier_income'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierWinLoseStastics::class;
    }
}
