<?php
namespace App\Models;

use App\Entities\CacheConstantPrefixDefine;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Log\PlayerDepositPayLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;

/**
 * Class CarrierPlayerLevel
 *
 * @package App\Models
 * @version February 27, 2017, 10:37 am UTC
 * @property int $id
 * @property string $level_name 用户等级名称
 * @property string $remark 备注
 * @property string $img 图片
 * @property bool $is_default 是否是默认等级 0否 1是
 * @property int $carrier_id 所属运营商id
 * @property bool $status 等级状态， 0 禁用 1 启用
 * @property bool $sort 排序
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property string $upgrade_rule 升级规则： json表示。格式见文档
 *           @mixin \Eloquent
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Map\CarrierPlayerLevelBankCardMap[] $bankCardMap
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarrierPlayerGamePlatRebateFinancialFlow[] $rebateFinancialFlow
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerLevel active()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerLevel byGamePlatId($gamePlat)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerLevel orderByDefault($type)
 * @property-read null|string $upgrade_rule_sql_string
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerLevel bySort($type)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPlayerLevel isDefault()
 */
class CarrierPlayerLevel extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        self::updated(function (CarrierPlayerLevel $level) {
            $carrier = Carrier::findOrFail($level->carrier_id);
            CarrierInfoCacheHelper::clearCachedAllActivePlayerLevelInfo($carrier);
        });
        self::created(function (CarrierPlayerLevel $level) {
            $carrier = Carrier::findOrFail($level->carrier_id);
            CarrierInfoCacheHelper::clearCachedAllActivePlayerLevelInfo($carrier);
        });
    }

    public $table = 'inf_carrier_player_level';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public static $levelUpdateSqlRules = [
        // 投注额, 计算有效投注额
        '$betAmount' => '(SELECT COALESCE(SUM(`available_bet_amount`),0) FROM `log_player_bet_flow` WHERE `player_id` = $player_id)',
        // 存款额, 必须是支付成功的存款数据
        '$depositAmount' => '(SELECT COALESCE(SUM(`amount`),0) FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND (`status` = ' . PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED . '))'
    ];

    protected $appends = [
        'upgrade_rule_sql_string'
    ];

    public $fillable = [
        'level_name',
        'remark',
        'is_default',
        'remark',
        'carrier_id',
        'status',
        'sort',
        'img',
        'upgrade_rule'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'level_name' => 'string',
        'remark' => 'string',
        'img' => 'string',
        'carrier_id' => 'integer',
        'upgrade_rule' => 'string',
        'status' => 'integer',
        'is_default' => 'integer',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'remark' => 'max:255',
        'upgrade_rule' => 'JSON|required',
        'status' => 'boolean'
    ];

    /**
     *
     * @var array
     */
    public static $requestAttributes = [
        'remark' => '备注',
        'upgrade_rule' => '升级规则',
        'status' => '状态',
        'is_default' => '默认等级',
        'level_name' => '等级名称',
        'sort' => '升级顺序'
    ];

    /**
     *
     * @param
     *            $player_id
     * @param
     *            $updateSqlRuleString
     * @return bool
     * @throws \Exception
     */
    public static function checkUserCanUpdateLevel($player_id, $updateSqlRuleString)
    {
        if (trim($updateSqlRuleString) == "") {
            return true;
        }
        try {
            $sqlRules = self::$levelUpdateSqlRules;
            array_walk($sqlRules, function (&$element) use ($player_id) {
                $element = preg_replace_array('/(\$player\_id)/', [
                    $player_id
                ], $element);
            });
            $sqlRules = array_merge($sqlRules, [
                '$player_id' => $player_id
            ]);
            $updateSqlRuleString = preg_replace('/(select|delete|database|show|drop|update|set|create|from|\*|\'|all|where)/', '', $updateSqlRuleString);
            foreach ($sqlRules as $field => $sql) {
                $updateSqlRuleString = preg_replace('/(\\' . $field . ')/', $sql, $updateSqlRuleString);
            }
            $executeSql = 'SELECT COUNT(1) AS `SUM` FROM `inf_player` WHERE `player_id` = ' . $player_id . ' AND ' . $updateSqlRuleString;
//            \WLog::info("会员等级升级规则检测sql:\n" . $executeSql);
            $sum = \DB::select($executeSql);
            return $sum ? ($sum[0]->SUM ? TRUE : FALSE) : FALSE;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 判断玩家是否能够升级
     *
     * @param
     *            $player_id
     * @return bool
     * @throws \Exception
     */
    public function playerCanUpgradeLevel($player_id)
    {
        $rule = $this->upgrade_rule_sql_string;
        if ($rule) {
            return self::checkUserCanUpdateLevel($player_id, $rule);
        }
        return false;
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrderByDefault(Builder $query, $type)
    {
        return $query->orderBy('is_default', $type);
    }

    public function scopeIsDefault(Builder $query)
    {
        return $query->where('is_default', true);
    }

    public function scopeBySort(Builder $query, $type)
    {
        return $query->orderBy('sort', $type);
    }

    public function scopeByGamePlatId(Builder $query, $gamePlat)
    {
        return $query->with([
            'rebateFinancialFlow' => function ($builder) use ($gamePlat) {
                $builder->byGamePlat($gamePlat);
            }
        ]);
    }

    /**
     * 默认等级类型集合
     *
     * @return array
     */
    public static function defaultLevelMeta()
    {
        return [
            0 => '否',
            1 => '是'
        ];
    }

    /**
     * 错误自定义消息
     *
     * @var array
     */
    public static $requestMessages = [
        'is_default.unique' => '默认等级只能设置一个,请先去掉设置过的默认等级'
    ];

    /**
     * 创建规则
     *
     * @param
     *            $current_carrier_id
     * @return array
     */
    public static function createRules($current_carrier_id)
    {
        return array_merge(self::$rules, [
            'level_name' => 'required|max:10|unique:inf_carrier_player_level,level_name,NULL,id,carrier_id,' . $current_carrier_id,
            'is_default' => 'required',
            'sort' => 'integer|min:1|max:99|required'
        ]);
    }

    /**
     * 更新规则
     *
     * @param
     *            $current_carrier_id
     * @param
     *            $except_id
     * @return array
     */
    public static function updateRules($current_carrier_id, $except_id)
    {
        return array_merge(self::$rules, [
            'level_name' => 'required|max:10|unique:inf_carrier_player_level,level_name,' . $except_id . ',id,carrier_id,' . $current_carrier_id,
            'is_default' => 'required',
            'sort' => 'integer|min:1|max:99'
        ]);
    }

    /**
     * 会员等级开放的银行卡列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bankCardMap()
    {
        return $this->hasMany(Map\CarrierPlayerLevelBankCardMap::class, 'carrier_player_level_id', 'id');
    }

    /**
     * 会员等级对应游戏平台的洗码配置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rebateFinancialFlow()
    {
        return $this->hasMany(CarrierPlayerGamePlatRebateFinancialFlow::class, 'carrier_player_level_id', 'id');
    }

    /**
     * 会员等级对应游戏平台的洗码配置缓存数据
     *
     * @return CarrierPlayerGamePlatRebateFinancialFlow
     */
    public static function cacheRebateFinancialFlow($playerLevelId, $gamePlatId)
    {
        return \Cache::remember(CacheConstantPrefixDefine::CARRIER_PLAYER_LEVEL_REBATE_FINANCIAL_FLOW_CONFIGURE_CACHE_PREFIX . $playerLevelId . '_' . $gamePlatId, 120, function () use ($gamePlatId, $playerLevelId) {
            return CarrierPlayerLevel::byGamePlatId($gamePlatId)->where('id', $playerLevelId)->first()->rebateFinancialFlow->first();
        });
    }

    /**
     * 获取当前等级升级策略sql
     *
     * @return null|string
     */
    public function getUpgradeRuleSqlStringAttribute()
    {
        if ($ruleJson = $this->upgrade_rule) {
            $ruleArray = json_decode($ruleJson, true);
            if (is_string($ruleArray[0])) {
                return $ruleArray[0];
            }
        }
        return null;
    }

    public function imageAsset(){
        return asset(\Storage::url('carrier/'.$this->img));
    }

}
