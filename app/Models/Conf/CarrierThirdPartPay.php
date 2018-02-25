<?php

namespace App\Models\Conf;

use App\Models\Def\PayChannel;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;


/**
 * App\Models\Conf\CarrierThirdPartPay
 *
 * @property int $id
 * @property int $carrier_id
 * @property int $def_pay_channel_id 三方支付平台ID
 * @property string $merchant_number 商户号
 * @property string $merchant_bind_domain 商户绑定域名
 * @property string $public_key 公钥
 * @property string $private_key 私钥
 * @property string $merchant_identify_code 商户识别码
 * @property \Carbon\Carbon $created_at
 * @property string $deleted_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\PayChannel $defPayChannel
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay retrieveByHost($host)
 * @mixin \Eloquent
 * @property string $good_name
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereDefPayChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereGoodName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereMerchantBindDomain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereMerchantIdentifyCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereMerchantNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay wherePrivateKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay wherePublicKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierThirdPartPay whereUpdatedAt($value)
 */
class CarrierThirdPartPay extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_third_part_pay';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_id',
        'def_pay_channel_id',
        'good_name',
        'merchant_number',
        'merchant_bind_domain',
        'private_key',
        'vir_card_no_in',
        'merchant_identify_code',
        'pay_ids_json'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'def_pay_channel_id' => 'integer',
        'merchant_number' => 'string',
        'merchant_bind_domain' => 'string',
        'good_name' => 'string',
        'private_key' => 'string',
        'vir_card_no_in' => 'string',
        'pay_ids_json' => 'string',
        'merchant_identify_code' => 'string'
    ];

    protected $hidden = ['private_key','public_key'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'merchant_number' => 'required|max:45',
    ];

    public static $requestAttributes = [
        'merchant_number' => '商户号',
    ];

    public function scopeRetrieveByHost(Builder $query,$host){
        return $query->where('merchant_bind_domain',$host);
    }
    
    /**
     * @param $current_carrier_id
     * @param $except_id
     * @return array
     */
    public static function updateRules($current_carrier_id, $except_id){
        return array_merge(self::$rules,[
            'merchant_number' => 'required|unique:conf_carrier_third_part_pay,merchant_bind_domain,'.$except_id.',id,carrier_id,'.$current_carrier_id
        ]);
    }

    /**
     * @param $current_carrier_id
     * @return array
     */
    public static function createRules($current_carrier_id){
        return array_merge(self::$rules,[
            'merchant_number' => 'required|unique:conf_carrier_third_part_pay,merchant_bind_domain,NULL,NULL,carrier_id,'.$current_carrier_id
        ]);
    }

    public function defPayChannel(){
        return $this->belongsTo(PayChannel::class,'def_pay_channel_id','id');
    }
    
}
