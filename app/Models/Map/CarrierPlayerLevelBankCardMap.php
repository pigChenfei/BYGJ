<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarrierPlayerLevelBankCardMap
 *
 * @package App\Models
 * @version March 6, 2017, 3:34 am UTC
 * @property int $map_id
 * @property int $carrier_player_level_id 对应的会员层级id
 * @property int $carrier_pay_channle_id 对应的银行卡id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarrierBankCard[] $carrierBankCards
 * @mixin \Eloquent
 */
class CarrierPlayerLevelBankCardMap extends Model
{

    public $table = 'map_carrier_player_level_pay_channel';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_player_level_id',
        'carrier_pay_channle_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'map_id' => 'integer',
        'carrier_player_level_id' => 'integer',
        'carrier_pay_channle_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function carrierBankCards()
    {
        return $this->hasOne(\App\Models\CarrierPayChannel::class,'id','carrier_pay_channle_id');
    }

    public function carrierPlayerLevel()
    {
        return $this->hasOne(\App\Models\CarrierPlayerLevel::class,'id','carrier_player_level_id');
    }
}
