<?php

namespace App\Models\Activity;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * Class CarrierActivityPlayerLevel
 *
 * @version April 4, 2017, 9:24 pm CST
 * @property int $id
 * @property int $act_id 活动ID
 * @property int $carrier_id 运营商ID
 * @property int $player_level_id 玩家等级ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class CarrierActivityPlayerLevel extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_activity_player_level';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'act_id',
        'carrier_id',
        'player_level_id'
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
        'player_level_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
