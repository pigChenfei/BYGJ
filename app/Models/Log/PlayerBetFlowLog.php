<?php
namespace App\Models\Log;

use App\Helpers\Caches\PlayerInfoCacheHelper;
use App\Jobs\PlayerUpgradeLevelHandle;
use App\Models\Def\Game;
use App\Models\Def\GamePlat;
use App\Models\Player;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Log\PlayerBetFlowLog
 *
 * @property int $id
 * @property int $player_id 玩家id
 * @property int $carrier_id
 * @property string $game_id 游戏id
 * @property int $game_plat_id 游戏平台id
 * @property string $game_flow_code 游戏流水号
 * @property int $player_or_banker 庄闲投注0无, 1庄 2闲 3庄闲都投注
 * @property int $is_handle 是否处理过
 * @property bool $game_status 游戏状态 1 结算完成, 0 未完成
 * @property mixed $bet_amount 下注金额
 * @property mixed $company_win_amount 公司输赢
 * @property mixed $available_bet_amount 有效投注额
 * @property mixed $company_payout_amount 公司派彩
 * @property bool $bet_flow_available 投注流水是否有效 1 有效 0无效
 * @property string $bet_info 投注内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed $progressive_bet 彩池投注额
 * @property mixed $progressive_win 彩池输赢
 * @property-read \App\Models\Def\Game $game
 * @property-read \App\Models\Def\GamePlat $gamePlat
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog betFlowAvailable()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog between($start_time, $end_time)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog byFinishTimeRange($startTime, $endTime)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog byPlayerId($playerId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog gameFinished()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerBetFlowLog unsettledInIds()
 *         @mixin \Eloquent
 */
class PlayerBetFlowLog extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
        // self::created(function(PlayerBetFlowLog $log){
        // if($log->bet_flow_available && $log->available_bet_amount > 0){
        // //玩家升级队列处理
        // dispatch(new PlayerUpgradeLevelHandle(PlayerInfoCacheHelper::getPlayerCacheInfoById($log->player_id)));
        // }
        // });
    }

    // 游戏结算完成
    const GAME_STATUS_FINISHED = 1;

    // 游戏结算未完成
    const GAME_STATUS_UNFINISHED = 0;

    // 投注流水有效
    const BET_FLOW_AVAILABLE = 1;

    // 投注流水无效
    const BET_FLOW_UNAVAILABLE = 0;

    // 庄闲投注 0 无, 1庄 2闲 3庄闲都投注
    const BET_FLOW_NONE = 0;

    const BET_FLOW_BANKER = 1;

    const BET_FLOW_PLAYER = 2;

    const BET_FLOW_PLAYER_AND_BANKER = 3;

    public $table = 'log_player_bet_flow';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'player_id',
        'carrier_id',
        'game_type',
        'game_plat_id',
        'game_id',
        'game_flow_code',
        'game_status',
        'bet_amount',
        'company_win_amount',
        'available_bet_amount',
        'company_payout_amount',
        'bet_flow_available',
        'player_or_banker',
        'bet_info',
        'progressive_bet',
        'progressive_win',
        'is_handle'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'player_id' => 'integer',
        'carrier_id' => 'integer',
        'game_plat_id' => 'integer',
        'bet_amount' => 'numeric',
        'company_win_amount' => 'numeric',
        'available_bet_amount' => 'numeric',
        'company_payout_amount' => 'numeric',
        'progressive_bet' => 'numeric',
        'progressive_win' => 'numeric',
        'game_id' => 'string',
        'game_status' => 'boolean',
        'game_flow_code' => 'string',
        'bet_flow_available' => 'boolean',
        'player_or_banker' => 'integer',
        'game_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public static function betFlowBankerPlayerMeta()
    {
        return [
            self::BET_FLOW_NONE => '无',
            self::BET_FLOW_PLAYER => '闲',
            self::BET_FLOW_PLAYER_AND_BANKER => '庄,闲',
            self::BET_FLOW_BANKER => '庄'
        ];
    }

    /**
     * 获取有效投注流水
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeBetFlowAvailable(Builder $query)
    {
        return $query->where('bet_flow_available', self::BET_FLOW_AVAILABLE);
    }

    public function scopeByPlayerId(Builder $query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByFinishTimeRange(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('created_at', [
            $startTime,
            $endTime
        ]);
    }

    // TODO 游戏平台常量设置
    // 限定获得PT平台的流水数据
    public function scopePt(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 4);
        });
    }

    // 限定获得AG平台的流水数据
    public function scopeAg(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 3);
        });
    }

    // 限定获得MG平台的流水数据
    public function scopeMg(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 2);
        });
    }

    // 限定获得BBIN平台的流水数据
    public function scopeBbin(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 1);
        });
    }

    // 限定获得申博平台的流水数据
    public function scopeSb(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 5);
        });
    }

    // 限定获得沙巴平台的流水数据
    public function scopeOw(Builder $query)
    {
        return $query->whereIn('game_plat_id', function ($query) {
            $query->select('game_plat_id')
                ->from(with(new GamePlat())->getTable())
                ->where('main_game_plat_id', 8);
        });
    }

    /**
     * 获取游戏结算完成
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeGameFinished(Builder $query)
    {
        return $query->where('game_status', self::GAME_STATUS_FINISHED);
    }

    public function scopeBetween(Builder $query, $start_time, $end_time)
    {
        return $query->whereBetween('created_at', [
            $start_time,
            $end_time
        ]);
    }

    public function scopeInGamePlats(Builder $query, array $gamePlatIds)
    {
        return $query->whereIn('game_plat_id', $gamePlatIds);
    }

    public function scopeUnsettledInIds(Builder $query, $playerIds)
    {
        return $query->whereIn('id', $playerIds);
    }

    /**
     * 获得游戏平台
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamePlat()
    {
        return $this->belongsTo(GamePlat::class, 'game_plat_id', 'game_plat_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    public function gameByType()
    {
        return $this->hasOne(Game::class, 'game_type', 'game_type');
    }

    // 统计投注记录
    public function scopePlayerBetLowLogSum(Builder $query, $log_player_bet_flow_id, $start_time, $end_time)
    {
        return $query->where('id', $log_player_bet_flow_id)->whereBetween('created_at', [
            $start_time,
            $end_time
        ]);
    }
}
