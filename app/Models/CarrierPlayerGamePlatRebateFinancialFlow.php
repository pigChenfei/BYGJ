<?php
namespace App\Models;

use App\Models\Def\GamePlat;
use App\Models\Log\PlayerRebateFinancialFlow;
use App\Models\Map\CarrierGamePlat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CarrierScope;

/**
 * 运营商会员等级与游戏平台对应的洗码比例
 * Class CarrierPlayerGamePlatRebateFinancialFlow
 *
 * @package App\Models
 * @version March 7, 2017, 2:08 am UTC
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $carrier_player_level_id 会员等级id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property int $limit_amount_per_flow 单次限额
 * @property float $rebate_financial_flow_rate 当前会员等级对应的游戏平台总洗码比例
 * @property string $rebate_financial_flow_step_rate_json 当前会员等级对应的游戏平台阶梯洗码比例 json
 * @property int $rebate_type 发放洗码方法 1 客服手动 2 会员自动获取洗码
 * @property int $rebate_manual_period_hours 客服手动洗码周期
 * @property-read \App\Models\Map\CarrierGamePlat $carrierGamePlat
 * @property-read \App\Models\CarrierPlayerLevel $carrierPlayerLevel @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerGamePlatRebateFinancialFlow byGamePlat($gamePlatId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerGamePlatRebateFinancialFlow autoRebateFinancial()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerGamePlatRebateFinancialFlow settlePeriodType($periodType)
 */
class CarrierPlayerGamePlatRebateFinancialFlow extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_player_game_plats_rebate_financial_flow';

    // 客服手动操作洗码
    const REBATE_TYPE_MANUAL = 1;

    //
    // 会员自动获取洗码
    const REBATE_TYPE_BY_PLAYER = 2;

    // 客服手动洗码周期 1 天
    const REBATE_MANUAL_PERIOD_DAY = 24;

    // rebate_manual_period_hours
    // 客服手动洗码周期 1 周
    const REBATE_MANUAL_PERIOD_WEEK = 24 * 7;

    const REBATE_MANUAL_PERIOD_MONTH = 24 * 30;

    const REBATE_AUTO_PERIOD_FOREVER = 0;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'limit_amount_per_flow',
        'rebate_financial_flow_rate',
        'rebate_financial_flow_step_rate_json',
        'rebate_type',
        'rebate_manual_period_hours'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'carrier_player_level_id' => 'integer',
        'carrier_game_plat_id' => 'integer',
        'limit_amount_per_flow' => 'numeric',
        'rebate_financial_flow_step_rate_json' => 'string',
        'rebate_type' => 'integer',
        'rebate_manual_period_hours' => 'integer'
    ];

    public function scopeByGamePlat(Builder $query, $gamePlatId)
    {
        return $query->where('carrier_game_plat_id', $gamePlatId);
    }

    public function scopeAutoRebateFinancial(Builder $query)
    {
        return $query->where('rebate_type', self::REBATE_TYPE_BY_PLAYER);
    }

    public function scopeSettlePeriodType(Builder $query, $periodType)
    {
        return $query->where('rebate_manual_period_hours', $periodType);
    }

    public function flowStepRateFormatString()
    {
        $array = json_decode($this->rebate_financial_flow_step_rate_json, true);
        if ($array) {
            $formatArray = array_map(
                function ($element) {
                    return '[' . $element['flowAmount'] . ',' . $element['flowRate'] . '%]';
                }, $array);
            return implode(' => ', $formatArray);
        }
        return '';
    }

    public function flowStepRateFormatArray()
    {
        $array = json_decode($this->rebate_financial_flow_step_rate_json, true);
        return $array ?: null;
    }

    public function isAutoRebateFinancialByPlayer()
    {
        return $this->rebate_type == self::REBATE_TYPE_BY_PLAYER;
    }

    public static function rebateTypeMeta()
    {
        return [
            self::REBATE_TYPE_MANUAL => '客服手动洗码',
            self::REBATE_TYPE_BY_PLAYER => '会员自助洗码'
        ];
    }

    public static function rebatePeriodMeta()
    {
        return [
            self::REBATE_MANUAL_PERIOD_DAY => '一天',
            self::REBATE_MANUAL_PERIOD_WEEK => '一周'
        ];
    }

    public static function rebatePeriodMetaAuto()
    {
        return [
            self::REBATE_MANUAL_PERIOD_WEEK => '一周内',
            self::REBATE_MANUAL_PERIOD_MONTH => '一月内',
            self::REBATE_AUTO_PERIOD_FOREVER => '永久'
        ];
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rebate_type' => 'required|in:' . self::REBATE_TYPE_MANUAL . ',' . self::REBATE_TYPE_BY_PLAYER,
//        'rebate_manual_period_hours' => 'required|in:' . self::REBATE_MANUAL_PERIOD_DAY . ',' . self::REBATE_MANUAL_PERIOD_WEEK,
        'rebate_manual_period_hours' => 'required',
            'limit_amount_per_flow' => 'required|numeric|min:0',
            'rebate_financial_flow_rate' => 'required|numeric|min:0|max:100',
            'rebate_financial_flow_step_rate_json' => 'validateFlowStepRateJson'
    ];

    public static $requestAttributes = [
        'rebate_type' => '洗码方式',
        'rebate_manual_period_hours' => '洗码周期',
        'limit_amount_per_flow' => '单次限额',
        'rebate_financial_flow_rate' => '总洗码比例'
    ];

    /**
     * 验证阶梯流水json
     *
     * @param
     *            $attribute
     * @param
     *            $value
     * @param
     *            $parameters
     * @param
     *            $validator
     * @return mixed
     */
    public function validateFlowStepRateJson($attribute, $value, $parameters, $validator)
    {
        if (! $value) {
            return $validator;
        }
        $valueArray = json_decode($value, true);
        if (! $valueArray) {
            $validator->errors()->add($attribute, '阶梯流水数据不能为空');
            return $validator;
        }
        foreach ($valueArray as $value) {
            if (! isset($value['flowAmount']) || ! isset($value['flowRate'])) {
                $validator->errors()->add($attribute, '阶梯流水数据不完整');
                return $validator;
            }
            if ($value['flowAmount'] < 0) {
                $validator->errors()->add($attribute, '有不合法的有效流水');
                return $validator;
            }
            if ($value['flowRate'] < 0 || $value['flowRate'] > 100) {
                $validator->errors()->add($attribute, '有不合法的洗码比例');
                return $validator;
            }
        }
        return $validator;
    }

    public function carrierPlayerLevel()
    {
        return $this->belongsTo(CarrierPlayerLevel::class, 'carrier_player_level_id', 'id');
    }

    public function carrierGamePlat()
    {
        return $this->belongsTo(CarrierGamePlat::class, 'carrier_game_plat_id', 'game_plat_id');
    }

    public function rebateFinancialFlows()
    {
        return $this->hasMany(PlayerRebateFinancialFlow::class, 'game_plat', 'carrier_game_plat_id');
    }
}
