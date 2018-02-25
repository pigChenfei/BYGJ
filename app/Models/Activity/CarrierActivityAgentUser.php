<?php

namespace App\Models\Activity;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * Class CarrierActivityAgentUser
 *
 * @version April 4, 2017, 8:59 pm CST
 * @property int $id
 * @property int $act_id 活动ID
 * @property int $carrier_id 运营商ID
 * @property int $agent_user_id 代理用户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class CarrierActivityAgentUser extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    
    public $table = 'inf_carrier_activity_agent_user';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'act_id',
        'carrier_id',
        'agent_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'act_id' => 'integer',
        'carrier_id' => 'integer',
        'agent_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
