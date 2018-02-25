<?php

namespace App\Models\Conf;

use App\Models\CarrierAgentLevel;
use App\Models\Def\GamePlat;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Map\CarrierGamePlat;
/**
 * Class CarrierRebateFinancialFlowAgent
 *
 * @package App\Models\Conf
 * @version April 14, 2017, 1:41 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id 代理类型名称id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property float $agent_rebate_financial_flow_max_amount 代理洗码上限
 * @property float $agent_rebate_financial_flow_rate 代理洗码比例
 * @property float $player_rebate_financial_flow_rate 玩家洗码比例
 * @property float $player_rebate_financial_flow_max_amount 玩家洗码上限
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\CarrierAgentLevel $agentLevel
 * @mixin \Eloquent
 */
class CarrierRebateFinancialFlowAgent extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_rebate_financial_flow_agent';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_level_id',
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
        'agent_level_id' => 'integer',
        'carrier_game_plat_id' => 'integer'
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
    public function agentLevel()
    {
        return $this->hasOne(CarrierAgentLevel::class,'id','agent_level_id');
    }
    
    public function carrierGamePlat(){
        return $this->belongsTo(CarrierGamePlat::class,'carrier_game_plat_id','game_plat_id');
    }

    public function gamePlat(){
        return $this->hasOne(GamePlat::class,'game_plat_id','carrier_game_plat_id');
    }
}
