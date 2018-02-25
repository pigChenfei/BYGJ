<?php
/**
 * Created by PhpStorm.
 * User: wangning
 * Date: 17/3/7
 * Time: 下午3:45
 */

namespace App\Repositories\Carrier;
use App\Models\Conf\CarrierAgentPlatformFeeSetting;
use InfyOm\Generator\Common\BaseRepository;


class CarrierAgentPlatformFeeSettingRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'carrier_id',
        'agent_level_id',
        'carrier_game_plat_id',
        'updated_at',
        'limit_amount_per_flow',
        'rebate_financial_flow_rate'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentPlatformFeeSetting::class;
    }


}