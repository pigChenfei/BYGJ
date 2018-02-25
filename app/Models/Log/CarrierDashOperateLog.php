<?php

namespace App\Models\Log;
use App\Jobs\UpdateLogModelIpAddressQueue;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * App\Models\Log\CarrierDashOperateLog
 *
 * @property int $id
 * @property int $carrier_id 所属运营商id
 * @property int $user_id 运营商用户id
 * @property int $route_id 路由id
 * @property int $data 操作数据json
 * @property string $ip ip
 * @property string $operate_place
 * @property int $status_code 状态码
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class CarrierDashOperateLog extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        CarrierDashOperateLog::created(function($log){
            dispatch((new UpdateLogModelIpAddressQueue($log,'ip','operate_place'))->onQueue('low'));
        });
    }

    public $table = 'log_carrier_dash_operate';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'carrier_id',
        'user_id',
        'route_id',
        'data',
        'ip',
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
        'user_id' => 'integer',
        'route_id' => 'integer',
        'data' => 'integer',
        'ip' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
