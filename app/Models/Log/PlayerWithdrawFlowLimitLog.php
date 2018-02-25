<?php
namespace App\Models\Log;

use App\Models\CarrierActivity;
use App\Models\Player;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Log\PlayerWithdrawFlowLimitLog
 *
 * @property int $id
 * @property int $carrier_id
 * @property int $player_id
 * @property int $player_account_log 关联的会员账户记录表
 * @property float $limit_amount 取款流水限制
 * @property int $limit_type 限额类型
 *           1 优惠活动
 *           2 自动洗码
 *           3 手动洗码
 *           4 调整红利
 *           5 会员存款
 *           6 调整余额
 *           7 调整洗码
 * @property float $complete_limit_amount 已完成的流水限制
 * @property bool $is_finished 是否已完成该流水限制
 * @property int $operator_id 处理人员 运营商用户id
 * @property int $related_activity 关联的活动id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\CarrierActivity $carrierActivity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat[] $limitGamePlats
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog byPlayerId($player_id)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog earliestUnfinishedLog()
 *         @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog hasActivity()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerWithdrawFlowLimitLogDetail[] $limitFlowCompleteDetail
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog finished()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog unfinished()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLog unsettledInIds($playerIds)
 */
class PlayerWithdrawFlowLimitLog extends Model
{
    
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
    }

    public $table = 'log_player_withdraw_flow_limit';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    // 优惠活动
    const LIMIT_TYPE_BENEFIT_ACTIVITY = 1;

    // 客服发放洗码
    const LIMIT_TYPE_AUTO_REBATE_FINANCIAL_FLOW = 2;

    // 会员自助洗码
    const LIMIT_TYPE_MANUAL_REBATE_FINANCIAL_FLOW = 3;

    // 调整红利
    const LIMIT_TYPE_ADJUST_BONUS = 4;

    // 会员存款
    const LIMIT_TYPE_PLAYER_DEPOSIT = 5;

    // 调整余额
    const LIMIT_TYPE_ADJUST_REMAIN_ACCOUNT = 6;

    // 调整洗码
    const LIMIT_TYPE_ADJUST_REBATE_FINANCIAL_FLOW = 7;

    // 邀请好友奖励
    const LIMIT_TYPE_PLAYER_INVITE_REWARD = 8;

    public $fillable = [
        'carrier_id',
        'player_id',
        'player_account_log',
        'limit_amount',
        'complete_limit_amount',
        'is_finished',
        'operator_id',
        'related_activity',
        'limit_type'
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
        'player_account_log' => 'integer',
        'is_finished' => 'boolean',
        'operator_id' => 'integer',
        'related_activity' => 'integer',
        'complete_limit_amount' => 'numeric',
        'limit_amount' => 'numeric',
        'limit_type' => 'integer'
    ];

    public static function limitTypeMeta()
    {
        return [
            self::LIMIT_TYPE_BENEFIT_ACTIVITY => '优惠活动',
            self::LIMIT_TYPE_AUTO_REBATE_FINANCIAL_FLOW => '自动洗码',
            self::LIMIT_TYPE_MANUAL_REBATE_FINANCIAL_FLOW => '手动洗码',
            self::LIMIT_TYPE_ADJUST_BONUS => '调整红利',
            self::LIMIT_TYPE_PLAYER_DEPOSIT => '会员存款优惠',
            self::LIMIT_TYPE_ADJUST_REMAIN_ACCOUNT => '调整余额',
            self::LIMIT_TYPE_ADJUST_REBATE_FINANCIAL_FLOW => '调整洗码',
            self::LIMIT_TYPE_PLAYER_INVITE_REWARD => '邀请好友奖励'
        ];
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public function limitGamePlats()
    {
        return $this->hasMany(PlayerWithdrawFlowLimitLogGamePlat::class, 'player_withdraw_flow_limit_id', 'id');
    }

    public function limitFlowCompleteDetail()
    {
        return $this->hasMany(PlayerWithdrawFlowLimitLogDetail::class, 'withdraw_flow_limit_id', 'id');
    }

    public function carrierActivity()
    {
        return $this->belongsTo(CarrierActivity::class, 'related_activity', 'id');
    }

    public function scopeByPlayerId(Builder $query, $player_id)
    {
        return $query->where('player_id', $player_id);
    }

    public function scopeUnfinished(Builder $query)
    {
        return $query->where('is_finished', false);
    }

    public function scopeFinished(Builder $query)
    {
        return $query->where('is_finished', true);
    }

    public function scopeEarliestUnfinishedLog(Builder $query)
    {
        return $query->unfinished()
            ->orderBy('id', 'ASC')
            ->limit(1);
    }

    public function scopeHasActivity(Builder $query)
    {
        return $query->whereNotNull('related_activity');
    }

    public function scopeUnsettledInIds(Builder $query, $playerIds)
    {
        return $query->whereIn('id', $playerIds);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];
}
