<?php

namespace App\Models\Activity;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * Class CarrierActivityAmphotericGamePlat
 *
 * @version April 4, 2017, 9:47 pm CST
 * @property int $id
 * @property int $act_id
 * @property int $carrier_id
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class CarrierActivityAmphotericGamePlat extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_activity_amphoteric_game_plat';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'act_id',
        'carrier_id',
        'carrier_game_plat_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'act_id' => 'integer',
        'carrier_id' => 'integer',
        'carrier_game_plat_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
