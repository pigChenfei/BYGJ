<?php

namespace App\Models;

use App\Models\Def\DefPin;
use Eloquent as Model;
/**
 * Class CarrierPin
 *
 * @package App\Models
 * @version April 17, 2017, 8:49 pm CST
 * @property int $id
 * @property int $carrier_id
 * @property int $pin_id 标签id
 * @property-read \App\Models\Carrier $carrier
 * @property-read \App\Models\Def\DefPin $defPin
 * @mixin \Eloquent
 */
class CarrierPin extends Model
{
    public $table = 'inf_carrier_pins';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'pin_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'pin_id' => 'integer'
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
    public function carrier()
    {
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function defPin()
    {
        return $this->belongsTo(DefPin::class,'pin_id','id');
    }
}
