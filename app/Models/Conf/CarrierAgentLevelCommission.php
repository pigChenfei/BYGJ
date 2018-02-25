<?php

namespace App\Models\Conf;

use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierSubordinateAgentCommission
 * @package App\Models\Conf
 * @version April 19, 2017, 11:15 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id 代理类型名称ID
 * @property int $level 代理等级
 * @property int $commission_max 佣金比例上限  0表示无上限
 * @property float $commission_ratio 佣金比例
 * @property \Carbon\Carbon $updated_at 修改时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $deleted_at 删除时间
 * @mixin \Eloquent
 */
class CarrierAgentLevelCommission extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    use SoftDeletes;

    public $table = 'conf_carrier_agent_level_commission';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_level_id',
        'level',
        'commission_ratio',
        'commission_max'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_level_id' => 'integer',
        'level' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


}
