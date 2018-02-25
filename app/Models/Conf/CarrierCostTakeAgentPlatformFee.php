<?php

namespace App\Models\Conf;
use App\Models\Map\CarrierGamePlat;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
/**
 * Class CarrierCostTakeAgentPlatformFee
 *
 * @package App\Models\Conf
 * @version April 13, 2017, 2:28 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property int $platform_fee_max 平台费上限
 * @property float $platform_fee_rate 平台费比例%
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\Map\CarrierGamePlat $carrierGamePlat
 * @mixin \Eloquent
 */
class CarrierCostTakeAgentPlatformFee extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_cost_take_agent_platform_fee';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_level_id',
        'carrier_game_plat_id',
        'platform_fee_max',
        'platform_fee_rate'
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
        'carrier_game_plat_id' => 'integer',
        'platform_fee_max' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function carrierGamePlat(){
        return $this->belongsTo(CarrierGamePlat::class,'carrier_game_plat_id','game_plat_id');
    }
}
