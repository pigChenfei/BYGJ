<?php
/**
 * Created by PhpStorm.
 * User: wangning
 * Date: 17/3/7
 * Time: 下午3:45
 */

namespace App\Repositories\Carrier;
use App\Models\Conf\CarrierAgentMultiLevelCommission;
use InfyOm\Generator\Common\BaseRepository;


class CarrierAgentMultiLevelCommissionRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'carrier_id',
        'agent_level_id',
        'updated_at',
        'deposit_discount_accept_ratio',
        'deposit_discount_accept_max',
        'rebate_financial_flow_accept_ratio',
        'rebate_financial_flow_accept_max',
        'bonus_accept_ratio',
        'commission_member',
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
        return CarrierAgentMultiLevelCommission::class;
    }
    
    /*
     * 获取单条数据
     */
    public function agentCommissionFind($input)
    {
        return $this->model->where($input)->first();
    }


}