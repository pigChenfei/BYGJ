<?php

namespace App\Models\Conf;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
/**
 * Class CarrierCostTakeAgent
 *
 * @package App\Models\Conf
 * @version April 13, 2017, 2:28 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id 代理类型名称ID
 * @property float $deposit_fee_undertake_ratio 存款手续费承担比例
 * @property int $deposit_fee_undertake_max 存款手续费承担上限
 * @property float $deposit_preferential_undertake_ratio 代理存款优惠承担比例 0不承担
 * @property int $deposit_preferential_undertake_max 代理存款优惠最高承担金额  0表示无上限
 * @property float $rebate_financial_flow_undertake_ratio 承担返水比例 0表示无上限
 * @property int $rebate_financial_flow_undertake_max 返水承担上线 0无上限
 * @property float $bonus_undertake_ratio 红利承担比例 0无上限
 * @property int $bonus_undertake_max 红利承担上限  0表示无上限
 * @property bool $can_player_join_activity 会员是否跟随网站优惠活动(是否能够参加优惠活动)
 * @property bool $is_player_rebate_financial_adapt_carrier_conf 会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算)
 * @property float $cost_take_ration 占成比例
 * @property int $protection_fund 保障金
 * @property \Carbon\Carbon $updated_at 修改时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @mixin \Eloquent
 */
class CarrierCostTakeAgent extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_cost_take_agent';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     *会员是否跟随网站优惠活动(是否能够参加优惠活动)
     * can_player_join_activity
     */
    const CAN_PLAYER_JOIN_ACTIVITY_IS = 1;
    const CAN_PLAYER_JOIN_ACTIVITY_NOT = 0;
    
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
        'deposit_fee_undertake_ratio',
        'deposit_fee_undertake_max',
        'deposit_preferential_undertake_ratio',
        'deposit_preferential_undertake_max',
        'rebate_financial_flow_undertake_ratio',
        'rebate_financial_flow_undertake_max',
        'bonus_undertake_ratio',
        'bonus_undertake_max',
        'is_multi_cost_take_agent',
        'can_player_join_activity',
        'is_player_rebate_financial_adapt_carrier_conf',
        'cost_take_ration',
        'protection_fund'
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
        'deposit_fee_undertake_max' => 'integer',
        'deposit_preferential_undertake_max' => 'integer',
        'rebate_financial_flow_undertake_max' => 'integer',
        'bonus_undertake_max' => 'integer',
        'protection_fund' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     *会员是否跟随网站优惠活动(是否能够参加优惠活动) 数据列表
     * can_player_join_activity
     */
    public static function canPlayerJoinActivityMeta(){
        return [
            self::CAN_PLAYER_JOIN_ACTIVITY_NOT => '否',
            self::CAN_PLAYER_JOIN_ACTIVITY_IS => '是'
        ];
    }
    
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
