<?php

namespace App\Models\Log;

use App\Models\Def\GamePlat;
use App\Models\Player;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlayerRebateFinancialFlow
 *
 * @package App\Models\Member
 * @version April 4, 2017, 11:38 pm CST
 * @property int $id
 * @property int $carrier_id
 * @property int $player_id
 * @property int $game_plat 游戏平台id
 * @property int $rebate_manual_period_hours 洗码周期
 * @property int $rebate_type 洗码类型
 * @property int $is_effect 有效状态
 * @property bool $log_player_bet_flow_id 对应id
 * @property float $bet_flow_amount 总投注流水
 * @property float $rebate_financial_flow_amount 洗码额
 * @property float $company_pay_out_amount 公司派彩总额
 * @property float $agent_out_amount 代理
 * @property float $company_out_amount 代理
 * @property bool $is_already_settled 是否已结算 1 已结算 0 未结算
 * @property int $settled_type 客服手动处理类型0未处理 1返水2返零
 * @property \Carbon\Carbon $settled_at 结算时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerRebateFinancialFlow byWithinPeriodUnsettledLog($playerId, $periodHours, $gamePlatId)
 * @mixin \Eloquent
 * @property-read \App\Models\Def\GamePlat $gamePlat
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerRebateFinancialFlow unsettled()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerRebateFinancialFlow unsettledInIds($playerIds)
 */
class PlayerRebateFinancialFlowNew extends Model
{
    use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
    }

    public $table = 'log_player_rebate_financial_flow_new';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_IS_SETTLED = 1;
    const STATUS_NO_SETTLED = 0;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'carrier_id',
        'player_id',
        'game_plat',
        'log_player_bet_flow_id',
        'bet_flow_amount',
        'company_pay_out_amount',
        'is_already_settled',
        'agent_out_amount',
        'company_out_amount',
        'rebate_manual_period_hours',
        'settled_at',
        'rebate_type',
        'settled_type',
        'is_effect',
        'rebate_financial_flow_amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'game_plat' => 'integer',
        'log_player_bet_flow_id' => 'integer',
        'is_already_settled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * 结算状态
     * @return array
     */
    public static function statusMeta(){
        return [
            self::STATUS_IS_SETTLED => '已结算',
            self::STATUS_NO_SETTLED => '未结算'
        ];
    }

    public function scopeUnsettled(Builder $query){
        return $query
            ->where('is_already_settled',false);
    }

    public function scopeByWithinPeriodUnsettledLog(Builder $query,$playerId,$periodHours,$gamePlatId){
        return $query
            ->whereBetween('created_at',[Carbon::now()->subHours($periodHours),Carbon::now()])
            ->where('game_plat',$gamePlatId)
            ->where('player_id',$playerId)
            ->unsettled();
    }

    public function scopeUnsettledInIds(Builder $query,$playerIds){
        return $query->whereIn('id',$playerIds)
            ->unsettled();
    }

    /**
     * 今天以前的数据
     * @param Builder $query
     */
    public function scopeEarlyThanToday(Builder $query){
        return $query->where('updated_at','<',Carbon::now()->startOfDay()->toDateTimeString());
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

    public function gamePlat(){
        return $this->belongsTo(GamePlat::class,'game_plat','game_plat_id');
    }
    public function playerBet(){
        return $this->hasOne(PlayerBetFlowLog::class,'id','log_player_bet_flow_id');
    }
}
