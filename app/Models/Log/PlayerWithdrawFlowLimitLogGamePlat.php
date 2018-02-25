<?php

namespace App\Models\Log;

use App\Models\Def\GamePlat;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerWithdrawFlowLimitLogGamePlat
 *
 * @package App\Models\Log
 * @version April 1, 2017, 11:24 pm CST
 * @property int $id
 * @property int $player_withdraw_flow_limit_id 流水限制记录id
 * @property int $def_game_plat_id 游戏平台id
 * @mixin \Eloquent
 * @property-read \App\Models\Def\GamePlat $gamePlat
 */
class PlayerWithdrawFlowLimitLogGamePlat extends Model
{

    public $table = 'log_player_withdraw_flow_limit_game_plats';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'player_withdraw_flow_limit_id',
        'def_game_plat_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'player_withdraw_flow_limit_id' => 'integer',
        'def_game_plat_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    public function gamePlat(){
        return $this->belongsTo(GamePlat::class,'def_game_plat_id','game_plat_id');
    }

    
}
