<?php

namespace App\Models\Log;

use App\Models\CarrierUser;
use App\Models\Player;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlayerAccountAdjustLog
 *
 * @package App\Models\Log
 * @version April 1, 2017, 4:18 pm CST
 * @property int $id
 * @property int $player_id 所属会员
 * @property bool $adjust_type 调整类型  1 存款 2 洗码 3 红利
 * @property int $operator 操作人
 * @property float $amount 调整金额
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 * @property-read \App\Models\Player $player
 * @property int $carrier_id
 * @property-read \App\Models\CarrierUser $operatorUser
 */
class PlayerAccountAdjustLog extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_player_account_adjust';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    //存款
    const ADJUST_TYPE_DEPOSIT = 1;
    //洗码
    const ADJUST_TYPE_REBATE_FINANCIAL_FLOW = 2;
    //红利
    const ADJUST_TYPE_BONUS = 3;

    public $fillable = [
        'player_id',
        'carrier_id',
        'adjust_type',
        'operator',
        'amount',
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
        'amount' => 'number',
        'player_id' => 'integer',
        'adjust_type' => 'integer',
        'operator' => 'integer',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules(){
        return [
            'player_id'   => 'required|exists:inf_player,player_id,carrier_id,'.\WinwinAuth::carrierUser()->carrier_id
        ];
    }

    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

    public function operatorUser(){
        return $this->belongsTo(CarrierUser::class,'operator','id');
    }

    public static function adjustTypeMeta(){
        return [
            self::ADJUST_TYPE_DEPOSIT => '存款',
            self::ADJUST_TYPE_REBATE_FINANCIAL_FLOW => '洗码',
            self::ADJUST_TYPE_BONUS   => '红利'
        ];
    }

    
}
