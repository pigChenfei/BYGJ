<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Def\Template;
use App\Models\Carrier;

class CarrierTemplate extends Model
{

    public $table = 'inf_carrier_template';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'carrier_id',
        'template_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'carrier_id' => 'integer',
        'template_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function carrier(){
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }

    public function templates(){
        return $this->belongsTo(Template::class,'template_id','id');
    }
}
