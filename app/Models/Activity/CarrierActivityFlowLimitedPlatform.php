<?php

namespace App\Models\Activity;
use App\Models\Def\GamePlat;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * Class CarrierActivityFlowLimitedPlatform
 *
 * @package App\Models\Member
 * @version April 4, 2017, 4:42 pm CST
 * @property int $id
 * @property int $act_id 活动ID
 * @property int $carrier_id 运营商ID
 * @property int $carrier_game_plat_id 运营商开放的游戏平台id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereActId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereCarrierGamePlatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Activity\CarrierActivityFlowLimitedPlatform whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierActivityFlowLimitedPlatform extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    
    public $table = 'inf_carrier_activity_flow_limited_game_plat';
    
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

    public function gamePlat(){
        return $this->hasOne(GamePlat::class,'game_plat_id','carrier_game_plat_id');
    }
    
}
