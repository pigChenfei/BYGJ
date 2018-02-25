<?php

namespace App\Models\Log;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
/**
 * Class AgentAccountLog
 * @package App\Models\Carrier
 * @version April 15, 2017, 9:32 pm CST
 */
class AgentAccountLog extends Model
{
    use SoftDeletes;
    
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    
    public $table = 'log_agent_account';
    
    protected $primaryKey = 'log_id';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    //存款
    const FUND_TYPE_DEPOSIT = 1;
    //取款
    const FUND_TYPE_WITHDRAW = 2;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'amount',
        'fund_type',
        'fund_source',
        'remark',
        'operator_reviewer_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'log_id' => 'integer',
        'carrier_id' => 'integer',
        'agent_id' => 'integer',
        'fund_type' => 'boolean',
        'fund_source' => 'string',
        'remark' => 'string',
        'operator_reviewer_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
