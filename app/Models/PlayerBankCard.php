<?php
namespace App\Models;

use App\Models\Def\BankType;
use App\Scopes\PlayerScope;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Yajra\Datatables\Html\Builder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PlayerBankCard
 *
 * @package App\Models\Member
 * @version March 9, 2017, 9:26 am UTC
 * @property int $card_id
 * @property int $player_id 所属会员
 * @property string $card_account 取款账号
 * @property bool $card_type 银行卡类型 外键
 * @property string $card_owner_name 持卡人姓名
 * @property string $card_birth_place 开户行地址
 * @property bool $status 是否有效 0无效 1有效
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\Def\BankType $bankType @mixin \Eloquent
 * @property int $carrier_id 运营商ID
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCardAccount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCardBirthPlace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCardOwnerName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCardType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard wherePlayerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerBankCard active()
 * @mixin \Eloquent
 */
class PlayerBankCard extends Model
{
    
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PlayerScope());
    }


    const STATUS_AVAILABLE = 1;

    const STATUS_UNAVAILABLE = 0;

    public $table = 'inf_player_bank_cards';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    protected $primaryKey = 'card_id';

    public $fillable = [
        'carrier_id',
        'player_id',
        'card_account',
        'card_type',
        'card_owner_name',
        'card_birth_place',
        'status'
    ];

    public static $requestAttributes = [
        'card_account' => '银行账号',
        'card_owner_name' => '开户人姓名',
        'card_type' => '银行卡类型',
        'card_birth_place' => '分行名称'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'card_id' => 'integer',
        'card_account' => 'string',
        'card_owner_name' => 'string',
        'card_birth_place' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * 判断银行卡号是否存在
     *
     * @param
     *            $card_account
     * @return bool
     */
    public static function bankAccount($card_account)
    {
        $isExist = self::where('card_account', $card_account)->first();
        if ($isExist) {
            throw new PayOrderRuntimeException("银行卡号已存在");
        }
        return true;
    }

    /**
     * 获取银行卡信息
     *
     * @param unknown $card_account
     * @return unknown
     */
    public static function bankCard($card_account)
    {
        return self::where('card_account', $card_account)->first();
    }

    /**
     * 银行卡状态
     *
     * @return array
     */
    public static function statusMeta()
    {
        return [
            self::STATUS_UNAVAILABLE => '无效',
            self::STATUS_AVAILABLE => '有效'
        ];
    }

    /**
     * 有效银行卡
     *
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function bankType()
    {
        return $this->belongsTo(BankType::class, 'card_type', 'type_id');
    }
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public static function replaceStar($str, $start, $length = 0)
    {
        $i = 0;
        $star = '';
        if ($start >= 0) {
            if ($length > 0) {
                $str_len = strlen($str);
                $count = $length;
                if ($start >= $str_len) { // 当开始的下标大于字符串长度的时候，就不做替换了
                    $count = 0;
                }
            } elseif ($length < 0) {
                $str_len = strlen($str);
                $count = abs($length);
                if ($start >= $str_len) { // 当开始的下标大于字符串长度的时候，由于是反向的，就从最后那个字符的下标开始
                    $start = $str_len - 1;
                }
                $offset = $start - $count + 1; // 起点下标减去数量，计算偏移量
                $count = $offset >= 0 ? abs($length) : ($start + 1); // 偏移量大于等于0说明没有超过最左边，小于0了说明超过了最左边，就用起点到最左边的长度
                $start = $offset >= 0 ? $offset : 0; // 从最左边或左边的某个位置开始
            } else {
                $str_len = strlen($str);
                $count = $str_len - $start; // 计算要替换的数量
            }
        } else {
            if ($length > 0) {
                $offset = abs($start);
                $count = $offset >= $length ? $length : $offset; // 大于等于长度的时候 没有超出最右边
            } elseif ($length < 0) {
                $str_len = strlen($str);
                $end = $str_len + $start; // 计算偏移的结尾值
                $offset = abs($start + $length) - 1; // 计算偏移量，由于都是负数就加起来
                $start = $str_len - $offset; // 计算起点值
                $start = $start >= 0 ? $start : 0;
                $count = $end - $start + 1;
            } else {
                $str_len = strlen($str);
                $count = $str_len + $start + 1; // 计算需要偏移的长度
                $start = 0;
            }
        }

        while ($i < $count) {
            $star .= '*';
            $i ++;
        }

        return substr_replace($str, $star, $start, $count);
    }
}
