<?php
namespace App\Models\Def;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PayChannelType
 *
 * @package App\Models
 * @version March 18, 2017, 7:41 am UTC
 * @property int $id
 * @property string $type_name 银行类型名称
 * @property int $parent_id 父类ID
 * @property bool $sort 排序
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\PayChannelType $parentPayChannelType
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereTypeName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\PayChannelType whereUpdatedAt($value)
 *         @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Def\PayChannel[] $parentPayChannelList
 */
class PayChannelType extends Model
{

    // 三方支付
    const THIRD_PART_PAY = 1;

    // 在线支付
    // const ONLINE_PAY = 2;
    const ONLINE_PAY = 8;

    // 这里先把在线支付当成网银支付，add by tlt
    // 扫码支付
    const SCAN_CODE_PAY = 3;

    // 公司
    const COMPANY_PART_PAY = 4;

    // 银行转账
    const BANK_TRANSFER_PAY = 5;

    // 扫码支付(公司)
    const SCAN_CODE_COMPANY_PAY = 6;

    // 点卡
    const COMPANY_CODE = 7;

    // 点卡支付
    const POINT_CARD_PAY = 8;

    // 在线支付/扫码支付
    const ONLINE_OR_SCAN_PAY = 9;

    const ONLINE_H5 = 10;

    public $table = 'def_pay_channel_type';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'type_name',
        'parent_id',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_name' => 'string',
        'parent_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * 根据父ID查询类型
     *
     * @return PayChannelType
     */
    public function parentPayChannelType()
    {
        return $this->belongsTo(PayChannelType::class, 'parent_id', 'id');
    }

    public function parentPayChannelList()
    {
        return $this->hasMany(PayChannel::class, 'pay_channel_type_id', 'id');
    }

    // 获取子集
    public function childInfo()
    {
        return $this->hasMany(PayChannelType::class, 'parent_id', 'id');
    }

    /**
     * 获取支付类型大分类
     *
     * @return PayChannelType[]
     */
    public static function topPayChannelTypes()
    {
        return self::where('parent_id', 0)->get();
    }

    /**
     * 返回所有的支付类型,包括其下级类型;
     *
     * @param
     *            $id
     * @return
     *
     */
    public static function allPayChannelTypesByTypeId($id, $top = true)
    {
        $allTypes = [];
        $types = self::where($top ? 'id' : 'parent_id', $id)->get();
        if ($types) {
            $allTypes = array_merge($allTypes, $types->toArray());
            foreach ($types as $type) {
                $allTypes = array_merge($allTypes, self::allPayChannelTypesByTypeId($type->id, false));
            }
        }
        return $allTypes;
    }

    /**
     * 是否是三方支付平台
     *
     * @return bool
     */
    public function isThirdPartPay()
    {
        $payChannelType = $this->parentPayChannelType;
        if ($payChannelType) {
            if ($payChannelType->id == self::THIRD_PART_PAY) {
                return true;
            }
            return $payChannelType->isThirdPartPay();
        }
        return false;
    }

    public function isWeb()
    {
        $payChannelType = $this->parentPayChannelType;
        if ($payChannelType) {
            if ($payChannelType->id == self::THIRD_PART_PAY && $this->id != self::ONLINE_H5) {
                return true;
            }
            return $payChannelType->isWeb();
        }
        return false;
    }

    public function isMobile()
    {
        $payChannelType = $this->parentPayChannelType;
        if ($payChannelType) {
            if ($payChannelType->id == self::THIRD_PART_PAY && in_array($this->id, [
                self::ONLINE_H5,
                self::ONLINE_OR_SCAN_PAY
            ])) {
                return true;
            }
            return $payChannelType->isMobile();
        }
        return false;
    }

    /**
     * 是否是公司支付
     *
     * @return bool
     */
    public function isCompanyPay()
    {
        $payChannelType = $this->parentPayChannelType;
        if ($payChannelType) {
            if ($payChannelType->id == self::COMPANY_PART_PAY) {
                return true;
            }
            return $payChannelType->isCompanyPay();
        }
        return false;
    }

    /**
     * 是否是公司转账
     *
     * @return bool
     */
    public function isCompanyBankTransfer()
    {
        if ($this->id == self::BANK_TRANSFER_PAY) {
            return true;
        }
        $payChannelType = $this->parentPayChannelType;
        if ($payChannelType) {
            if ($payChannelType->id == self::BANK_TRANSFER_PAY) {
                return true;
            }
            return $payChannelType->isCompanyBankTransfer();
        }
        return false;
    }
}
