<?php

namespace App\Models\Log;
use App\Models\CarrierUser;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\CarrierAgentUser;

/**
 * Class AgentAccountAdjustLog
 * @package App\Models\Log\AgentAccountAdjustLog
 * @version April 15, 2017, 6:22 pm CST
 */
class AgentAccountAdjustLog extends Model
{
    
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    
    public $table = 'log_agent_account_adjust';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    /**
     *调整类型
     * Deposit 存款adjust_type
     * @var type 
     */
    const ADJUST_TYPE_DEPOSIT = 1;
    /**
     *调整类型
     * Commission 佣金adjust_type
     * @var type 
     */
    const ADJUST_TYPE_COMMISSION = 2;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'agent_id',
        'carrier_id',
        'adjust_type',
        'operator',
        'amount',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'carrier_id' => 'integer',
        'operator' => 'integer',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
   
    /**
     * 调整类型
     * adjust_type
     */
    public static function adjustTypeMeta()
    {
        return [
            self::ADJUST_TYPE_DEPOSIT => '存款',
            self::ADJUST_TYPE_COMMISSION => '佣金',
        ];
    }
    
    public function operatorUser(){
        return $this->belongsTo(CarrierUser::class,'operator','id');
    }
    
    public function agent(){
        return $this->belongsTo(CarrierAgentUser::class,'agent_id','id');
    }
    
}
