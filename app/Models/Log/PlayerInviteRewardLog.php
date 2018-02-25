<?php

namespace App\Models\Log;

use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Log\PlayerInviteRewardLog
 *
 * @property int $id
 * @property int $carrier_id
 * @property int $player_id 奖励的会员对象id
 * @property bool $reward_type 奖励类型  1  投注额奖励   2  存款奖励
 * @property int $reward_related_player 奖励出自于哪一个会员id
 * @property float $reward_amount 奖励金额
 * @property float $related_player_deposit_amount 关联的会员总存款额
 * @property float $related_player_bet_amount 关联的会员投注额
 * @property float $related_player_validate_bet_amount 关联的会员有效投注额
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerInviteRewardLog byRewardType($type)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerInviteRewardLog latestLog()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerInviteRewardLog byPlayerId($playerId)
 * @mixin \Eloquent
 */
class PlayerInviteRewardLog extends Model
{
    use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
    }


    /**
     *存款奖励
     */
    const SETTLE_TYPE_DEPOSIT = 2;
    /**
     *投注额奖励
     */
    const SETTLE_TYPE_BET = 1;
    //玩家自身投注奖励
    const SETTLE_TYPE_SELF_BET = 3;
    //玩家自身存款奖励
    const SETTLE_TYPE_SELF_DEPOSIT = 4;

    public $table = 'log_player_invite_reward';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'player_id',
        'reward_type',
        'reward_related_player',
        'reward_amount',
        'related_player_deposit_amount',
        'related_player_bet_amount',
        'related_player_validate_bet_amount'
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
        'reward_type' => 'integer',
        'reward_related_player' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * 奖励类型
     * @return array
     */
    public static function rewardType(){
        return [
            self::SETTLE_TYPE_BET => '投注额奖励',
            self::SETTLE_TYPE_DEPOSIT => '存款奖励',
            self::SETTLE_TYPE_SELF_BET => '自身投注额奖励',
            self::SETTLE_TYPE_SELF_DEPOSIT => '自身存款奖励'
        ];
    }

    public function scopeByPlayerId(Builder $query,$playerId){
        return $query->where('player_id',$playerId);
    }

    public function scopeByRewardType(Builder $query,$type){
        return $query->where('reward_type',$type);
    }

    public function scopeLatestLog(Builder $query){
        return $query->orderBy('created_at','desc')->limit(1);
    }


    
}
