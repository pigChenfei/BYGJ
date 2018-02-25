<?php

namespace App\Models\Log;

use App\Models\CarrierAgentUser;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
/**
 * Class CarrierCommissionSettlePeriodsLog
 * @package App\Models\Carrier
 * @version April 19, 2017, 8:45 pm CST
 */
class CarrierAgentSettlePeriodsLog extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_agent_settle_periods';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'periods',
        'start_time',
        'end_time',
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
        'periods' => 'string',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static function lastSettlePeriodsId(){
        return self::max('id');
    }
    
    public function commissionSettle(){
        return $this->hasOne(CarrierAgentSettleLog::class,'periods_id','id');
    }

    public function agentUser(){
        return $this->belongsTo(CarrierAgentUser::class,'agent_id','parent_id');
    }
    
}
