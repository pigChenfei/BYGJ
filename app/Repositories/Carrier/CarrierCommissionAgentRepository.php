<?php
/**
 * Created by PhpStorm.
 * User: wangning
 * Date: 17/3/7
 * Time: 下午3:45
 */

namespace App\Repositories\Carrier;
use App\Models\Conf\CarrierCommissionAgent;
use InfyOm\Generator\Common\BaseRepository;


class CarrierCommissionAgentRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'carrier_id',
        'agent_level_id',
        'updated_at',
        'deposit_fee_undertake_ratio',
        'deposit_fee_undertake_max',
        'deposit_preferential_undertake_ratio',
        'deposit_preferential_undertake_max',
        'rebate_financial_flow_undertake_ratio',
        'rebate_financial_flow_undertake_max',
        'bonus_undertake_ratio',
        'bonus_undertake_max',
        'available_member_monthly_bet_amount',
        'available_member_count',
        'max_commission_amount_per_time',
        'commission_ratio',
        'commission_step_ratio',
        'is_multi_commission_agent',
        'sub_commission_ratio'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierCommissionAgent::class;
    }
    
    /*
     * 获取单条数据
     */
    public function commissionAgentFind($input)
    {
        return $this->model->where($input)->first();
    }


}