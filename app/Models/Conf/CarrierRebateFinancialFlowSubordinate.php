<?php

namespace App\Models\Conf;

use App\Models\Def\GamePlat;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierRebateFinancialFlowSubordinate
 * @package App\Models\Log
 * @version May 11, 2017, 10:58 pm CST
 */
class CarrierRebateFinancialFlowSubordinate extends Model
{
    //use SoftDeletes;

    public $table = 'conf_carrier_rebate_financial_flow_subordinate_agent';
    
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

    public function gamePlat(){
        return $this->hasOne(GamePlat::class,'game_plat_id','carrier_game_plat_id');
    }

    
}
