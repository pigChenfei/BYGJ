<?php
namespace App\Models;

use App\Models\Conf\CarrierCommissionAgent;
use App\Models\Conf\CarrierCommissionAgentPlatformFee;
use App\Models\Conf\CarrierCostTakeAgent;
use App\Models\Conf\CarrierCostTakeAgentPlatformFee;
use App\Models\Conf\CarrierRebateFinancialFlowAgent;
use App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo;
use Illuminate\Database\Eloquent\Builder;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\Conf\CarrierAgentLevelCommission;

/**
 * App\Models\CarrierAgentLevel
 *
 * @property int $id
 * @property int $carrier_id 所属运营商
 * @property string $level_name 层级名称
 * @property bool $type 代理类型 1佣金代理，2洗码代理，3占成代理
 * @property int $default_player_level 代理下属玩家默认层级
 * @property bool $is_default 是否是代理默认层级 0否 1是
 * @property bool $is_running 是否启用, 1 启用 0, 禁用
 * @property bool $is_multi_agent 是否支持多级代理
 * @property bool $sort 排序字段
 * @property string $remark
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property-read \App\Models\Conf\CarrierCommissionAgent $commissionAgentConf
 * @property-read \App\Models\Conf\CarrierCommissionAgentPlatformFee $commissionAgentPlatformFeeConf
 * @property-read \App\Models\Conf\CarrierCostTakeAgent $costTakeAgentConf
 * @property-read \App\Models\Conf\CarrierCostTakeAgentPlatformFee $costTakeAgentPlatformFeeConf
 * @property-read \App\Models\CarrierPlayerLevel $defaultPlayerLevel
 * @property-read \App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo $rebateFinancialFlowAgentBaseConf
 * @property-read \App\Models\Conf\CarrierRebateFinancialFlowAgent $rebateFinancialFlowAgentGamePlatConf
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierAgentLevel active()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierAgentLevel orderByDefault($type)
 *         @mixin \Eloquent
 */
class CarrierAgentLevel extends Model
{

    // use SoftDeletes;
    /**
     * 佣金代理
     * Commission agent
     */
    const COMMISSION_AGETN = 1;

    /**
     * 洗码代理
     * Code washing agent
     */
    const REBATE_FINANCIAL_FLOW_AGENT = 2;

    /**
     * 占成代理
     * Accounting agent
     */
    const COST_TAKE_AGENT = 3;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_agent_level';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'level_name',
        'type',
        'default_player_level',
        'is_multi_agent',
        'is_default',
        'is_running',
        'sort',
        'remark',
        'update_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'level_name' => 'string',
        'remark' => 'string',
        'default_player_level' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'remark' => 'max:255',
        'is_running' => 'boolean',
        'is_default' => 'boolean',
        'is_multi_agent' => 'boolean',
        'level_name' => 'required|max:45'
        // 'deposit_discount_accept_ratio' => 'min:0|max:100|numeric|required',
    ];

    public static $requestAttributes = [
        'remark' => '备注',
        'is_running' => '状态',
        'is_default' => '默认代理',
        'level_name' => '代理名称',
        'sort' => '排序'
        // 'deposit_discount_accept_ratio' => '代理存款优惠承担比例',
    ];

    public static $requestMessages = [
        'is_default.unique' => '默认代理只能设置一个,请先去掉设置过的默认代理'
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_running', 1);
    }

    public function scopeOrderByDefault(Builder $query, $type)
    {
        return $query->orderBy('is_running', $type);
    }

    public static function defaultAgentLevelMeta()
    {
        return [
            0 => '否',
            1 => '是'
        ];
    }

    /**
     * 是否支持多级代理
     *
     * @return type
     */
    public static function multiAgentMeta()
    {
        return [
            0 => '否',
            1 => '是'
        ];
    }

    public function defaultPlayerLevel()
    {
        return $this->belongsTo(CarrierPlayerLevel::class, 'default_player_level', 'id');
    }

    public static function createRules($current_carrier_id)
    {
        return array_merge(self::$rules, [
            'level_name' => 'required|max:10|unique:inf_carrier_agent_level,level_name,NULL,id,carrier_id,' . $current_carrier_id,
//            'is_default' => 'required|boolean|unique:inf_carrier_agent_level,is_default,NULL,id,is_default,1',
            'sort' => 'integer|min:1|max:99|required'
        ]);
    }

    public static function updateRules($current_carrier_id, $except_id)
    {
        return array_merge(self::$rules, [
            'level_name' => 'required|max:10|unique:inf_carrier_agent_level,level_name,' . $except_id . ',id,carrier_id,' . $current_carrier_id,
            // 'is_default' => 'required|boolean|unique:inf_carrier_agent_level,is_default,' . $except_id . ',id,is_default,1',
            'sort' => 'integer|min:1|max:99'
        ]);
    }

    /**
     * 获取类型字典数据
     *
     * @return array
     */
    public static function typeMeta()
    {
        return [
            self::COMMISSION_AGETN => '佣金代理',
            self::REBATE_FINANCIAL_FLOW_AGENT => '洗码代理'
            // self::COST_TAKE_AGENT => '占成代理',
        ];
    }

    /**
     *
     * @return bool 是否是洗码代理
     */
    public function isRebateFinancialFlowAgent()
    {
        return $this->type == self::REBATE_FINANCIAL_FLOW_AGENT;
    }

    /**
     * 是否是占成代理
     *
     * @return bool
     */
    public function isCostTakeAgent()
    {
        return $this->type == self::COST_TAKE_AGENT;
    }

    /**
     * 是否是佣金代理
     *
     * @return bool
     */
    public function isCommissionAgent()
    {
        return $this->type == self::COMMISSION_AGETN;
    }

    /**
     * 洗码代理游戏平台设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rebateFinancialFlowAgentGamePlatConf()
    {
        return $this->hasMany(CarrierRebateFinancialFlowAgent::class, 'agent_level_id', 'id');
    }

    /**
     * 洗码代理基本设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rebateFinancialFlowAgentBaseConf()
    {
        return $this->hasOne(CarrierRebateFinancialFlowAgentBaseInfo::class, 'agent_level_id', 'id');
    }

    /**
     * 佣金代理基本设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commissionAgentConf()
    {
        return $this->hasOne(CarrierCommissionAgent::class, 'agent_level_id', 'id');
    }

    /**
     * 佣金代理平台费设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commissionAgentPlatformFeeConf()
    {
        return $this->hasOne(CarrierCommissionAgentPlatformFee::class, 'agent_level_id', 'id');
    }

    /**
     * 占成代理基本设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function costTakeAgentConf()
    {
        return $this->hasOne(CarrierCostTakeAgent::class, 'agent_level_id', 'id');
    }

    /**
     * 占成代理平台费设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function costTakeAgentPlatformFeeConf()
    {
        return $this->hasOne(CarrierCostTakeAgentPlatformFee::class, 'agent_level_id', 'id');
    }

    /**
     * 获取佣金代理层级佣金设置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agentLevelCommission()
    {
        return $this->hasMany(CarrierAgentLevelCommission::class, 'agent_level_id', 'id');
    }
}
