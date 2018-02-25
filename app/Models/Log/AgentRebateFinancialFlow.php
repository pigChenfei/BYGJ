<?php
namespace App\Models\Log;

use App\Models\Def\GamePlat;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AgentRebateFinancialFlow
 *
 * @package App\Models\Log
 * @version April 14, 2017, 1:59 pm CST
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property int $agent_id 代理用户ID
 * @property int $game_plat_id 游戏平台id
 * @property float $amount 金额
 * @property float $cathectic 投注额
 * @property float $available_cathectic 有效投注额
 * @property int $log_player_bet_flow_id 投注记录ID
 * @property int $log_agent_settled_id 代理佣金结算ID
 * @property float $flow_rate 洗码比例
 * @property bool $is_settled 是否已计算 0未结算，1已结算
 * @property string $settled_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Log\AgentRebateFinancialFlow $commissionSettleLog
 * @property-read \App\Models\Log\PlayerBetFlowLog $playerBetFlowLog @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentRebateFinancialFlow earliest()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentRebateFinancialFlow unsettled()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentRebateFinancialFlow byAgentId($agentId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentRebateFinancialFlow byGamePlatId($gamePlatId)
 */
class AgentRebateFinancialFlow extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_agent_rebate_financial_flow';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'agent_id',
        'amount',
        'cathectic',
        'available_cathectic',
        'log_player_bet_flow_id',
        'log_agent_settled_id',
        'flow_rate',
        'is_settled',
        'settled_at',
        'game_plat_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_id' => 'integer',
        'amount' => 'numeric',
        'log_player_bet_flow_id' => 'integer',
        'log_agent_settled_id' => 'integer',
        'is_settled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function scopeUnsettled(Builder $query)
    {
        return $query->where('is_settled', false);
    }

    public function scopeByGamePlatId(Builder $query, $gamePlatId)
    {
        return $query->where('game_plat_id', $gamePlatId);
    }

    public function scopeByAgentId(Builder $query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeEarliest(Builder $query)
    {
        return $query->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function playerBetFlowLog()
    {
        return $this->hasOne(PlayerBetFlowLog::class, 'id', 'log_player_bet_flow_id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function commissionSettleLog()
    {
        return $this->belongsTo(CarrierAgentSettleLog::class, 'log_agent_settled_id', 'id');
    }

    public function gamePlat()
    {
        return $this->belongsTo(GamePlat::class, 'game_plat_id', 'game_plat_id');
    }
}
