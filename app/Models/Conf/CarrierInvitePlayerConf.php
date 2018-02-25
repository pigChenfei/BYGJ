<?php

namespace App\Models\Conf;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierInvitePlayerConf
 *
 * @package App\Models\Conf
 * @version April 18, 2017, 10:22 pm CST
 * @property int $id
 * @property int $carrier_id
 * @property string $bet_reward_rule 投注额奖励规则
 * @property int $bet_reward_settle_period 投注额奖励结算周期  按天还是按周计算  默认 7天(一周)
 * @property string $deposit_reward_rule 存款额奖励规则
 * @property int $deposit_reward_settle_period 存款额奖励结算周期  按天还是按周计算  默认 7天(一周)
 * @property float $invalid_player_deposit_amount 有效会员达到的存款金额条件
 * @property float $invalid_player_bet_amount 有效会员达到的投注金额条件
 * @mixin \Eloquent
 */
class CarrierInvitePlayerConf extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        self::updated(function(CarrierInvitePlayerConf $conf){
            CarrierInfoCacheHelper::clearCachedInvitePlayerConf($conf->carrier);
        });
    }

    const SETTLE_PERIOD_DAY = 1;
    const SETTLE_PERIOD_WEEK = 7;

    public $table = 'conf_carrier_invite_player';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'bet_reward_rule',
        'bet_reward_settle_period',
        'deposit_reward_rule',
        'deposit_reward_settle_period',
        'invalid_player_deposit_amount',
        'invalid_player_bet_amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'bet_reward_rule' => 'string',
        'bet_reward_settle_peroid' => 'integer',
        'deposit_reward_rule' => 'string',
        'deposit_reward_settle_peroid' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function carrier(){
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }

    
}
