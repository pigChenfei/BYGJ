<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Eloquent as Model;
use App\Scopes\CarrierScope;
/**
 * Class CarrierActivityType
 *
 * @package App\Models
 * @version March 11, 2017, 6:39 am UTC
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property string $type_name 活动类型名称
 * @property string $desc 活动描述
 * @property bool $status 活动类型状态 1正常 0关闭
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType active()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType orderByDefault($type)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereTypeName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierActivityType extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    public $table = 'inf_carrier_activity_type';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'carrier_id',
        'type_name',
        'desc',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'type_name' => 'string',
        'desc' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type_name' => 'required|max:30',
    ];

    public static $requestAttributes = [
        'type_name' => '活动类型名称',
    ];
    
    public static function createRules($current_carrier_id){
        return array_merge(self::$rules,[
            'type_name' => 'required|max:15|unique:inf_carrier_activity_type,type_name,NULL,id,carrier_id,'.$current_carrier_id,
        ]);
    }

    public static function updateRules($current_carrier_id,$except_id){
        return array_merge(self::$rules,[
            'type_name' => 'required|max:15|unique:inf_carrier_activity_type,type_name,'.$except_id.',id,carrier_id,'.$current_carrier_id,
        ]);
    }
    
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrderByDefault(Builder $query,$type){
        return $query->orderBy('id',$type);
    }
    
}
