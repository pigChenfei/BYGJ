<?php

namespace App\Models\Log;

use App\Models\Def\Game;
use App\Models\Def\GamePlat;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Log\PlayerWithdrawFlowLimitLogDetail
 *
 * @property int $id
 * @property int $withdraw_flow_limit_id 流水限制id
 * @property int $game_plat_id 游戏平台
 * @property float $flow_amount 投注流水
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\GamePlat $gamePlat
 * @property-read \App\Models\Log\PlayerWithdrawFlowLimitLog $playerWithdrawFlowLimit
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLogDetail byFlowLimitLogId($flow_limit_log_id)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawFlowLimitLogDetail byGamePlatId($game_plat_id)
 * @mixin \Eloquent
 * @property int $game_id 游戏id
 * @property \Carbon\Carbon $deleted_at
 */
class PlayerWithdrawFlowLimitLogDetail extends Model
{
    use SoftDeletes;

    public $table = 'log_player_withdraw_flow_limit_detail';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'withdraw_flow_limit_id',
        'game_plat_id',
        'flow_amount',
        'game_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'withdraw_flow_limit_id' => 'integer',
        'game_plat_id' => 'integer',
        'game_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function playerWithdrawFlowLimit()
    {
        return $this->belongsTo(PlayerWithdrawFlowLimitLog::class,'withdraw_flow_limit_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function gamePlat()
    {
        return $this->belongsTo(GamePlat::class,'game_plat_id','game_plat_id');
    }

    public function game(){
        return $this->belongsTo(Game::class,'game_id','game_id');
    }

    public function scopeByFlowLimitLogId(Builder $query,$flow_limit_log_id){
        return $query->where('withdraw_flow_limit_id',$flow_limit_log_id);
    }

    public function scopeByGamePlatId(Builder $query,$game_plat_id){
        return $query->where('game_plat_id',$game_plat_id);
    }
}
