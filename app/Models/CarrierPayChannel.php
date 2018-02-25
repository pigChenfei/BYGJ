<?php
namespace App\Models;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Conf\CarrierThirdPartPay;
use App\Models\Map\CarrierPlayerLevelBankCardMap;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use Eloquent as Model;

/**
 * Class CarrierPayChannel
 *
 * @package App\Models
 * @version March 18, 2017, 7:46 am UTC
 * @property int $id
 * @property int $carrier_id 所属运营商
 * @property bool $def_pay_channel_id 支付渠道类型ID
 * @property int $binded_third_part_pay_id 第三方支付绑定配置id
 * @property float $balance 银行卡余额
 * @property string $account 卡号
 * 1,传统银行，此处必须填写银行卡的卡号，必须填写正确
 * 2,三方支付银行，此处可以填写商户ID
 * 3,互联网银行，此处必须填写账号，比如微信账号或者支付宝账号
 * @property string $owner_name 持卡人姓名 银行卡的持卡人姓名（如果该卡用于会员存款，这个信息一定要保持正确，否则会员将无法正确存款
 * @property int $fee_bear_id 手续费承担方
 * @property float $fee_ratio 手续费
 * @property float $default_preferential_ratio 默认优惠比例
 * 如果该卡用于存款，每发生一笔存款时，赠送会员的存款优惠比例，默认=0，表示不发放存款优惠
 * 如果设置为1，此时默认比例=1%，假设存款100进入，赠送的存款优惠=100×1%=1
 * @property int $balance_notify_amount 余额限额提醒,该银行卡的余额达到余额限额提醒时，在客服对会员存款审核的界面上，将提醒该卡余额超限
 * 默认=0，代表不提醒，如果设置为10000，则该银行卡余额超过10000时会被提醒
 * @property bool $status 启用 1、禁用 0、作废 -1
 *
 * 启用：启用中的银行卡，可以在网站前台在会员进行存款操作中被看到，或者客户进行取款操作时被看到
 * 禁用：禁用中的银行卡，可以在客户管理后台进行相关操作，但会员在网站前台无法看到
 * 作废：作废中的银行卡，不能被客服看到，注意禁用时会检查银行卡的余额，余额不为0的银行卡，不能被禁用
 * @property string $qrcode 二维码
 * 该设置仅用于互联网银行，提供二维码图片给会员进行存款扫描，此处设置图片存储路径
 * 比如：/fimg/i201509d603d4d41ceeaeceeed4.png
 * @property bool $use_purpose 用途:
 * 1 存款：如果该卡用于存款，则必须选择该项，系统中至少应该有一张用于存款的银行卡
 * 2 取款：如果该卡用于给会员出款，则必须选择该项
 * 3 库房：如果该卡既不是存款又不用于取款，则可设为库房
 * 注意：系统不允许同一张银行卡既用于存款又用于取款或者库房
 * @property string $card_origin_place 开户行
 * 1,传统银行，此处必须填写银行卡的开户行，比如：河南郑州工行解放路分理处
 * 2,三方支付银行，此处可以随意填写一些标识信息
 * 3,互联网银行，此处可以随意填写一些标识信息
 * @property bool $show 展示位置
 * @property int $single_day_deposit_limit 单日存款次数限制
 * @property int $single_deposit_minimum 单次存款最小限额
 * @property int $maximum_single_deposit 单次存款最大限额
 * @property string $remark 备注
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property string $deleted_at
 * @property-read \App\Models\Def\PayChannel $payChannel @mixin \Eloquent
 * @property-read \App\Models\Conf\CarrierThirdPartPay $bindedThirdPartGateway
 * @property string $display_name 前台展示名称
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPayChannel available()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierPayChannel shown()
 */
class CarrierPayChannel extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        self::updated(function () {
            CarrierInfoCacheHelper::clearAllCachedCarrierPayChannels();
        });
        self::created(function () {
            CarrierInfoCacheHelper::clearAllCachedCarrierPayChannels();
        });
    }

    /**
     * 公司承担方
     * fee_bear_id
     */
    const FEE_BEAR_COMPANY = 1;

    /**
     * 代理承担方
     * player
     */
    const FEE_BEAR_AGENT = 3;

    /**
     * 会员承担方
     * player
     */
    const FEE_BEAR_PLAYER = 2;

    /**
     * 用于存款
     */
    const USED_FOR_DEPOSIT = 1;

    /**
     * 用于取款
     */
    const USED_FOR_WITHDRAW = 2;

    /**
     * 用于仓库
     */
    const USED_FOR_STOREHOUSE = 3;

    /**
     * 可用状态
     */
    const STATUS_AVAILABLE = 1;

    /**
     * 禁用状态
     */
    const STATUS_FORBIDDEN = 0;

    /**
     * 废弃状态
     */
    const STATUS_ABANDONED = - 1;

    /**
     * 网页手机版
     */
    const SHOW_WEB_PHONE = 1;

    /**
     * 网页版
     */
    const SHOW_WEB = 2;

    /**
     * 手机版
     */
    const SHOW_PHONE = 3;

    // 手动调整转账记录类型
    // 转账
    const MANUAL_ADJUST_AMOUNT_TRANSFER = 1;

    // 股东入账
    const MANUAL_ADJUST_AMOUNT_SHAREHOLDER_ENTER = 2;

    // 存入库房
    const MANUAL_ADJUST_DEPOSIT_STORAGE = 3;

    public $table = 'inf_carrier_pay_channel';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_id',
        'def_pay_channel_id',
        'binded_third_part_pay_id',
        'account',
        'owner_name',
        'display_name',
        'qrcode',
        'default_preferential_ratio',
        'single_day_deposit_limit',
        'single_deposit_minimum',
        'maximum_single_deposit',
        'balance_notify_amount',
        'status',
        'fee_ratio',
        'use_purpose',
        'card_origin_place',
        'fee_bear_id',
        'remark',
        'show'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'account' => 'string',
        'display_name' => 'string',
        'owner_name' => 'string',
        'balance_notify_amount' => 'integer',
        'card_origin_place' => 'string',
        'remark' => 'string',
        'binded_third_part_pay_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'owner_name' => 'required|max:15',
        'card_origin_place' => 'required|max:255',
        'account' => 'required|max:45',
        'remark' => 'max:255',
        'display_name' => 'required|max:45',
        'balance_notify_amount' => 'min:0|integer',
        'single_day_deposit_limit' => 'integer',
        'single_deposit_minimum' => 'integer',
        'maximum_single_deposit' => 'integer',
        'use_purpose' => 'required|in:1,2,3',
        'def_pay_channel_id' => 'required|integer'
    ];

    public static $requestAttributes = [
        'owner_name' => '持卡人',
        'display_name' => '前台展示名称',
        'account' => '账号',
        'card_origin_place' => '开户行',
        'remark' => '备注',
        'balance_notify_amount' => '余额限额提醒',
        'single_day_deposit_limit' => '单日存款次数限制',
        'single_deposit_minimum' => '单次存款最小限额',
        'maximum_single_deposit' => '单次存款最大限额',
        'use_purpose' => '用途',
        'def_pay_channel_id' => '支付渠道'
    
    ];

    /**
     * 银行卡用途数据列表
     */
    public static function usedForPurposeMeta()
    {
        return [
            self::USED_FOR_DEPOSIT => '存款',
            self::USED_FOR_WITHDRAW => '取款',
            self::USED_FOR_STOREHOUSE => '仓库'
        ];
    }

    /**
     * 手续费承担方数据列表
     * fee_bear_id
     */
    public static function feeBearMeta()
    {
        return [
            self::FEE_BEAR_COMPANY => '公司',
            self::FEE_BEAR_AGENT => '代理',
            self::FEE_BEAR_PLAYER => '会员'
        ];
    }

    /**
     * 获取状态字典数据
     *
     * @return array
     */
    public static function statusMeta()
    {
        return [
            self::STATUS_AVAILABLE => '启用',
            self::STATUS_FORBIDDEN => '禁用'
        ];
    }

    public static function showMeta()
    {
        return [
            self::SHOW_WEB_PHONE => '网页版/手机版',
            self::SHOW_WEB => '网页版',
            self::SHOW_PHONE => '手机版'
        ];
    }

    public static function manualAdjustAmountTypeMeta()
    {
        return [
            self::MANUAL_ADJUST_AMOUNT_TRANSFER => '转账',
            self::MANUAL_ADJUST_AMOUNT_SHAREHOLDER_ENTER => '股东入金',
            self::MANUAL_ADJUST_DEPOSIT_STORAGE => '存入库房'
        ];
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeWithdrawPurpose(Builder $query)
    {
        return $query->where('use_purpose', self::USED_FOR_WITHDRAW);
    }

    /**
     *
     * @param $current_carrier_id
     * @param $except_id
     * @return array
     */
    public static function updateRules($current_carrier_id, $except_id)
    {
        return array_merge(self::$rules,
            [ // 'account' => 'required|unique:inf_carrier_pay_channel,account,'.$except_id.',id,carrier_id,'.$current_carrier_id.'|alpha_num|max:45'
]);
    }

    /**
     *
     * @param $current_carrier_id
     * @return array
     */
    public static function createRules($current_carrier_id)
    {
        return array_merge(self::$rules,
            [
                'account' => 'required|unique:inf_carrier_pay_channel,account,NULL,NULL,carrier_id,' .
                     $current_carrier_id . '|alpha_num|max:45'
            ]);
    }

    /*
     * 支付渠道ID查询分类
     */
    public function payChannel()
    {
        return $this->belongsTo(Def\PayChannel::class, 'def_pay_channel_id', 'id');
    }

    public function bindedThirdPartGateway()
    {
        return $this->belongsTo(CarrierThirdPartPay::class, 'binded_third_part_pay_id', 'id');
    }

    public function levelBankcard()
    {
        return $this->hasMany(CarrierPlayerLevelBankCardMap::class, 'carrier_pay_channle_id', 'id');
    }
}
