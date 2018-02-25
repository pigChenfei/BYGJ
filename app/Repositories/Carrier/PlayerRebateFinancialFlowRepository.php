<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerRebateFinancialFlow;
use InfyOm\Generator\Common\BaseRepository;

class PlayerRebateFinancialFlowRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'player_id',
        'game_plat',
        'bet_times',
        'rebate_financial_flow_amount',
        'bet_flow_amount',
        'company_pay_out_amount',
        'is_already_settled'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerRebateFinancialFlow::class;
    }
}
