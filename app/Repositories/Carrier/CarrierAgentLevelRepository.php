<?php

namespace App\Repositories\Carrier;
use App\Models\CarrierAgentLevel;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentLevelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'level_name',
        'default_player_level',
        'is_default',
        'is_running',
        'sort',
        'updated_at',
        'deposit_discount_accept_ratio',
        'deposit_discount_accept_max',
        'rebate_financial_flow_accept_ratio',
        'rebate_financial_flow_accept_max',
        'bonus_accept_ratio',
        'bonus_accept_max',
        'available_member_monthly_bet_account',
        'available_member_commission_account',
        'commission_per_max_account',
        'commission_ratio',
        'commission_step_ratio'
    ];

    
    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentLevel::class;
    }
    
    /**
     * 获取代理类型数据
     * @param type $carrierid
     * @return type
     */
    public function allAgentLevels()
    {
        return $this->scopeQuery(function(){
            return $this->model->active()->OrderByDefault('desc');
        })->all();
    }
}
