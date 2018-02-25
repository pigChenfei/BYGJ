<?php

namespace App\Models\Conf;

use App\Models\Def\GamePlat;
use App\Models\Map\CarrierGamePlat;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RebateFinancialFlowAgentGamePlatConf
 *
 * @package App\Models\Conf
 * @version April 14, 2017, 1:37 pm CST
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property int $agent_id 代理id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property float $agent_rebate_financial_flow_max_amount 代理洗码上限
 * @property float $agent_rebate_financial_flow_rate 代理洗码比例
 * @property float $player_rebate_financial_flow_rate 玩家洗码比例
 * @property float $player_rebate_financial_flow_max_amount 玩家洗码上限
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\RebateFinancialFlowAgentGamePlatConf byAgentId($agentId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\RebateFinancialFlowAgentGamePlatConf byGamePlatId($gamePlatId)
 */
class RebateFinancialFlowAgentGamePlatConf extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }


    public $table = 'conf_rebate_financial_flow_agent_game_plat';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'carrier_game_plat_id',
        'agent_rebate_financial_flow_max_amount',
        'agent_rebate_financial_flow_rate',
        'player_rebate_financial_flow_rate',
        'player_rebate_financial_flow_max_amount'
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
        'carrier_game_plat_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    public function scopeByGamePlatId(Builder $query,$gamePlatId){
        return $query->where('carrier_game_plat_id',$gamePlatId);
    }

    public function scopeByAgentId(Builder $query, $agentId){
        return $query->where('agent_id', $agentId);
    }
    public function carrierGamePlat(){
        return $this->belongsTo(CarrierGamePlat::class,'carrier_game_plat_id','game_plat_id');
    }

    public function gamePlat(){
        return $this->hasOne(GamePlat::class,'game_plat_id','carrier_game_plat_id');
    }
}
