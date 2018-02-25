<?php

namespace App\Models\Conf;

use Eloquent as Model;
use App\Scopes\CarrierScope;

/**
 * Class CarrierRebateFinancialFlowAgentBaseInfo
 *
 * @package App\Models\Carrier
 * @version April 13, 2017, 2:30 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id
 * @property int $available_member_monthly_bet_amount 有效会员当月投注额
 * @property int $available_member_count
 * @property bool $is_player_rebate_financial_adapt_carrier_conf 会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class CarrierRebateFinancialFlowAgentBaseInfo extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_rebate_financial_flow_agent_base_info';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     *会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算)
     * is_player_rebate_financial_adapt_carrier_conf
     */
    const PLAYER_REBATE_FINANCIAL_ADAPT_CARRIER_CONF_IS = 1;
    const PLAYER_REBATE_FINANCIAL_ADAPT_CARRIER_CONF_NOT = 0;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_level_id',
        'available_member_monthly_bet_amount',
        'available_member_count',
        'is_multi_rebate_financial_flow_agent',
        'is_player_rebate_financial_adapt_carrier_conf'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_level_id' => 'integer',
        'available_member_monthly_bet_amount' => 'integer',
        'available_member_count' => 'integer',
        'is_multi_rebate_financial_flow_agent' => 'boolean',
        'is_player_rebate_financial_adapt_carrier_conf' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     *会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算) 数据列表
     * is_player_rebate_financial_adapt_carrier_conf
     */
    public static function playerRebateFinancialAdaptCarrierConfMeta(){
        return [
            self::PLAYER_REBATE_FINANCIAL_ADAPT_CARRIER_CONF_NOT => '否',
            self::PLAYER_REBATE_FINANCIAL_ADAPT_CARRIER_CONF_IS => '是'
        ];
    }
}
