<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\CarrierUser;

/**
 * Class CarrierAgentNews
 * @package App\Models
 * @version May 9, 2017, 2:14 pm CST
 */
class CarrierAgentNews extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_agent_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'title',
        'remark',
        'operator_reviewer_id'
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

    /**
     * 操作人员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceUser(){
        return $this->belongsTo(CarrierUser::class,'operator_reviewer_id','id');
    }
}
