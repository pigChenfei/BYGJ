<?php

namespace App\Models\Conf;

use App\Models\Def\GamePlat;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierSubordinateAgentCommission
 * @package App\Models\Conf
 * @version April 19, 2017, 11:15 pm CST
 */
class CarrierSubordinateAgentCommission extends Model
{
    use SoftDeletes;

    public $table = 'conf_carrier_subordinate_agent_commission';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'commission_ratio'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


}
