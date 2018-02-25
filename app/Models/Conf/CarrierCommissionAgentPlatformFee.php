<?php
namespace App\Models\Conf;

use Eloquent as Model;
use App\Models\Map\CarrierGamePlat;
use App\Scopes\CarrierScope;

/**
 * Class CarrierCommissionAgentPlatformFee
 *
 * @package App\Models\Conf
 * @version April 13, 2017, 2:27 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property float $platform_fee_max 平台费上限
 * @property float $platform_fee_rate 平台费比例%
 * @property float $agent_rebate_financial_flow_rate 代理洗码比例%
 * @property float $agent_rebate_financial_flow_max_amount 代理洗码上限
 * @property float $computing_mode 计算模式
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\Map\CarrierGamePlat $carrierGamePlat @mixin \Eloquent
 */
class CarrierCommissionAgentPlatformFee extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_commission_agent_platform_fee';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'agent_level_id',
        'carrier_game_plat_id',
        'platform_fee_max',
        'platform_fee_rate',
        'agent_rebate_financial_flow_rate',
        'agent_rebate_financial_flow_max_amount',
        'computing_mode',
        'computing_mode_2'
    ];

    const COMMISSION_COMPUTING_MODE = 0;

    // 按佣金计算
    const BET_COMPUTING_MODE = 1;

    // 按有效投注额计算
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_level_id' => 'integer',
        'carrier_game_plat_id' => 'integer',
        'platform_fee_max' => 'numeric',
        'agent_rebate_financial_flow_rate' => 'numeric',
        'agent_rebate_financial_flow_max_amount' => 'numeric',
        'computing_mode' => 'integer',
        'computing_mode_2' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function carrierGamePlat()
    {
        return $this->belongsTo(CarrierGamePlat::class, 'carrier_game_plat_id', 'game_plat_id');
    }

    /**
     * 计算方式
     *
     * @return array
     */
    public static function computingModeMeta()
    {
        return [
            self::COMMISSION_COMPUTING_MODE => '按佣金计算',
            self::BET_COMPUTING_MODE => '按有效投注额计算'
        ];
    }
}
