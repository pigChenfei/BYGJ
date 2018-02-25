<?php

namespace App\Models\Def;

use Eloquent as Model;

/**
 * Class DefPin
 * @package App\Models
 * @version April 17, 2017, 8:48 pm CST
 */
class DefPin extends Model
{

    public $table = 'def_pins';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

}
