<?php

namespace App\Models\Conf;

use App\Models\Carrier;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Conf\CarrierDepositConf
 *
 * @property int $id
 * @property int $carrier_id
 * @property bool $is_allow_player_deposit 会员是否允许存款
 * @property bool $is_allow_agent_deposit 代理是否允许存款
 * @property bool $is_allow_third_part_deposit_auto_arrival 三方存款是否允许自动到账, 如果是 则不需要客服审核即可到账. 如果有优惠自动给会员返优惠
 * @property int $unreview_deposit_record_limit 允许未审核存款条数：设置条数，超出的自动过期消失
 * @property bool $third_part_deposit_is_open 三方存款是否开启
 * @property bool $company_deposit_is_open 公司存款是否开启:公司包括转账汇款，扫码支付（公司入款）
 * @property bool $is_allow_company_deposit_auto_arrival 公司存款是否自动到账；是或否（公司存款方式肯定是否，一般都是要审核的）
 * @property bool $virtual_card_deposit_is_open 点卡存款是否开启
 * @property bool $is_allow_virtual_card_deposit_auto_arrival 点卡存款是否自动到账
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\Carrier $carrier
 * @mixin \Eloquent
 */
class CarrierDepositConf extends Model
{
    //use SoftDeletes;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_deposit';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const IS_ALLOW_PLAYER_DEPOSIT = 'is_allow_player_deposit';
    const IS_ALLOW_AGENT_DEPOSIT = 'is_allow_agent_deposit';
    const IS_ALLOW_THIRD_PART_DEPOSIT_AUTO_ARRIVAL = 'is_allow_third_part_deposit_auto_arrival';
    const THIRD_PART_DEPOSIT_IS_OPEN = 'third_part_deposit_is_open';
    const COMPANY_DEPOSIT_IS_OPEN = 'company_deposit_is_open';
    const IS_ALLOW_COMPANY_DEPOSIT_AUTO_ARRIVAL = 'is_allow_company_deposit_auto_arrival';
    const VIRTUAL_CARD_DEPOSIT_IS_OPEN = 'virtual_card_deposit_is_open';
    const IS_ALLOW_VIRTUAL_CARD_DEPOSIT_AUTO_ARRIVAL = 'is_allow_virtual_card_deposit_auto_arrival';
    /**
     *是
     */
    const STATUS_OPEN = 1;
    /**
     *否
     */
    const STATUS_CLOSE = 0;

    public static function statusMeta(){
        return [
            self::STATUS_OPEN => '是',
            self::STATUS_CLOSE => '否',
        ];
    }

    public static function depositStatus(){
        return [
            self::IS_ALLOW_PLAYER_DEPOSIT => '是否允许会员存款',
            self::IS_ALLOW_AGENT_DEPOSIT => '是否允许代理存款',
            self::THIRD_PART_DEPOSIT_IS_OPEN => '三方存款是否开启',
            self::IS_ALLOW_THIRD_PART_DEPOSIT_AUTO_ARRIVAL => '三方存款是否自动到账',
            self::COMPANY_DEPOSIT_IS_OPEN => '公司存款是否开启',
            self::IS_ALLOW_COMPANY_DEPOSIT_AUTO_ARRIVAL => '公司存款是否自动到账',
            self::VIRTUAL_CARD_DEPOSIT_IS_OPEN => '点卡存款是否开启',
            self::IS_ALLOW_VIRTUAL_CARD_DEPOSIT_AUTO_ARRIVAL => '点卡存款是否自动到账'
        ];
    }


    //protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'is_allow_player_deposit',
        'is_allow_agent_deposit',
        'is_allow_third_part_deposit_auto_arrival',
        'unreview_deposit_record_limit',
        'third_part_deposit_is_open',
        'company_deposit_is_open',
        'is_allow_company_deposit_auto_arrival',
        'virtual_card_deposit_is_open',
        'is_allow_virtual_card_deposit_auto_arrival'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'unreview_deposit_record_limit' => 'integer',

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
    public function carrier()
    {
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }
}
