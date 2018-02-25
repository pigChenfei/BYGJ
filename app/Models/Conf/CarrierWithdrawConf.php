<?php

namespace App\Models\Conf;

use Eloquent as Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierWithdrawConf
 *
 * @package App\Models\Conf
 * @version March 22, 2017, 11:24 am UTC
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property bool $is_allow_player_withdraw 是否允许会员取款
 * @property bool $is_allow_player_withdraw_decimal 是否允许会员取款小数:如(0.88)
 * @property int $player_day_withdraw_success_limit_count 会员单日取款成功限制次数
 * @property int $player_day_withdraw_max_sum 会员单日取款最大金额
 * @property int $player_once_withdraw_max_sum 会员单次取款最大金额
 * @property int $player_once_withdraw_min_sum 会员单次取款最小金额
 * @property bool $is_display_flow_water_check 是否显示流水检查:开启后在取款页面完成流水限制的可直接取款，未完成的提示需完成流水多少
 * @property bool $is_check_flow_water_when_withdraw 取款是否检查流水
 * @property bool $is_open_risk_management_check 是否开启风控审核
 * @property bool $is_allow_agent_withdraw 是否允许代理取款
 * @property bool $is_allow_agent_withdraw_decimal 是否允许取款小数
 * @property int $agent_day_withdraw_success_limit_count 代理单日取款成功限制次数
 * @property int $agent_day_withdraw_max_sum 代理单日取款最大金额
 * @property int $agent_once_withdraw_max_sum 代理单次取款最大金额
 * @property int $agent_once_withdraw_min_sum 代理单次取款最小金额
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 */
class CarrierWithdrawConf extends Model
{
    //use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_withdraw';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const IS_ALLOW_PLAYER_WITHDRAW = 'is_allow_player_withdraw';
    const IS_ALLOW_PLAYER_WITHDRAW_DECIMAL = 'is_allow_player_withdraw_decimal';
    const IS_DISPLAY_FLOW_WATER_CHECK = 'is_display_flow_water_check';
    const IS_OPEN_RISK_MANAGEMENT_CHECK = 'is_open_risk_management_check';
    const IS_ALLOW_AGENT_WITHDRAW = 'is_allow_agent_withdraw';
    const IS_ALLOW_AGENT_WITHDRAW_DECIMAL = 'is_allow_agent_withdraw_decimal';
    const IS_CHECK_FLOW_WATER_WHEN_WITHDRAW = 'is_check_flow_water_when_withdraw';
    //protected $dates = ['deleted_at'];

    /**
     *是
     */
    const STATUS_OPEN = 1;
    /**
     *否
     */
    const STATUS_CLOSE = 0;

    public static function statusMeta(){
        return [
            self::STATUS_OPEN => '是',
            self::STATUS_CLOSE => '否',
        ];
    }

    public static function  playerStatus(){
        return [
            self::IS_ALLOW_PLAYER_WITHDRAW => '是否允许会员取款',
            self::IS_ALLOW_PLAYER_WITHDRAW_DECIMAL => '是否允许取款小数',
            self::IS_DISPLAY_FLOW_WATER_CHECK => '是否显示流水提示',
            self::IS_CHECK_FLOW_WATER_WHEN_WITHDRAW => '是否开启流水检测',
            self::IS_OPEN_RISK_MANAGEMENT_CHECK => '是否开启风控审核',
        ];
    }

    public static function  agentStatus(){
        return [
            self::IS_ALLOW_AGENT_WITHDRAW => '是否允许代理取款',
            self::IS_ALLOW_AGENT_WITHDRAW_DECIMAL => '是否允许取款小数',
        ];
    }

    public $fillable = [
        'carrier_id',
        'is_allow_player_withdraw',
        'is_allow_player_withdraw_decimal',
        'is_open_risk_management_check',
        'player_day_withdraw_success_limit_count',
        'player_day_withdraw_max_sum',
        'player_once_withdraw_max_sum',
        'player_once_withdraw_min_sum',
        'is_display_flow_water_check',
        'is_check_flow_water_when_withdraw',
        'is_allow_agent_withdraw',
        'is_allow_agent_withdraw_decimal',
        'agent_day_withdraw_success_limit_count',
        'agent_day_withdraw_max_sum',
        'agent_once_withdraw_max_sum',
        'agent_once_withdraw_min_sum'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'player_day_withdraw_max_sum' => 'integer',
        'player_once_withdraw_max_sum' => 'integer',
        'player_once_withdraw_min_sum' => 'integer',
        'agent_day_withdraw_max_sum' => 'integer',
        'agent_once_withdraw_max_sum' => 'integer',
        'agent_once_withdraw_min_sum' => 'integer',
        'player_day_withdraw_success_limit_count' => 'integer',
        'agent_day_withdraw_success_limit_count' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
