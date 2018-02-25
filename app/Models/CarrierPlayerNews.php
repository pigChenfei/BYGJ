<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\CarrierUser;

/**
 * Class CarrierPlayerNewsLog
 * @package App\Models\Log
 * @version May 8, 2017, 3:21 pm CST
 */
class CarrierPlayerNews extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_player_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'title',
        'operator_reviewer_id',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'title' => 'string',
        'operator_reviewer_id' => 'integer',
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
     * 操作人员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceUser(){
        return $this->belongsTo(CarrierUser::class,'operator_reviewer_id','id');
    }
    
}
