<?php

namespace App\Models\Log;

use App\Models\Def\GamePlat;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GameWinLoseStastics
 *
 * @package App\Models\Log
 * @version May 3, 2017, 4:55 pm CST
 * @property int $id
 * @property int $carrier_id
 * @property int $game_plat_id 游戏平台id
 * @property int $bet_player_count 投注人数
 * @property int $bet_count 投注次数
 * @property float $bet_amount 投注额
 * @property float $win_lose_amount 公司输赢
 * @property float $rebate_financial_flow_amount 洗码金额
 * @property float $average_bet_amount 人均投注额
 * @property float $average_bet_count 人均投注次数
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @mixin \Eloquent
 */
class GameWinLoseStastics extends Model
{
    use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_game_win_lose_stastics';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'game_plat_id',
        'bet_player_count',
        'bet_count',
        'bet_amount',
        'win_lose_amount',
        'rebate_financial_flow_amount',
        'average_bet_amount',
        'average_bet_count'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'game_plat_id' => 'integer',
        'bet_player_count' => 'integer',
        'bet_count' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function gamePlat(){
        return $this->belongsTo(GamePlat::class, 'game_plat_id', 'game_plat_id');
    }

    
}
