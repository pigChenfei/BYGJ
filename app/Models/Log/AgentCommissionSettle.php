<?php

namespace App\Models\Log;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AgentCommissionSettle
 * @package App\Models\Log
 * @version April 14, 2017, 2:16 pm CST
 */
class AgentCommissionSettle extends Model
{
    use SoftDeletes;

    public $table = 'log_agent_commission_settle';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'available_member_number',
        'game_plat_win_amount',
        'available_player_bet_amount',
        'cost_share',
        'cumulative_last_month',
        'manual_tuneup',
        'this_period_commission',
        'actual_payment',
        'transfer_next_month',
        'status',
        'remark',
        'created_user_id'
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
        'available_member_number' => 'string',
        'available_player_bet_amount' => 'integer',
        'cost_share' => 'integer',
        'cumulative_last_month' => 'integer',
        'manual_tuneup' => 'integer',
        'this_period_commission' => 'integer',
        'actual_payment' => 'integer',
        'transfer_next_month' => 'integer',
        'status' => 'boolean',
        'remark' => 'string',
        'created_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logAgentRebateFinancialFlows()
    {
        return $this->hasMany(\App\Models\Log\LogAgentRebateFinancialFlow::class);
    }
}
