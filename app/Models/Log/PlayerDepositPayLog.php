<?php
namespace App\Models\Log;

use App\Models\Carrier;
use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\CarrierUser;
use App\Models\Log\Base\BaseDepositModel;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Log\PlayerDepositPayLog
 *
 * @property int $id
 * @property string $pay_order_number 订单编号
 * @property int $carrier_id
 * @property string $pay_order_channel_trade_number 与支付平台的交易号
 * @property int $player_id 玩家id
 * @property int $player_bank_card 会员银行卡 仅线下存款有效
 * @property int $carrier_pay_channel 运营商入款支付渠道 仅线下存款有效
 * @property float $amount 存款金额
 * @property float $finally_amount 实际到账金额, 如果有红利或者优惠 实际金额可能大于存款金额
 * @property float $benefit_amount 优惠金额
 * @property float $bonus_amount 红利金额
 * @property float $withdraw_flow_limit_amount 取款流水限制
 * @property float $fee_amount 手续费
 * @property int $is_fee_amt 是否是会员承担手续费 1 是
 * @property int $carrier_activity_id 会员参与的活动id
 * @property bool $status 订单状态 0 订单创建 1 订单支付成功 -1 订单支付失败 -2审核未通过 2订单待审核
 * @property int $review_user_id 审核人员id
 * @property string $operate_time 处理时间
 * @property string $credential 凭据
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at
 * @property bool $offline_transfer_deposit_type 线下转账存款方式 1 ATM机 2 银行转账
 * @property string $offline_transfer_deposit_at 线下转账会员存款时间
 * @property string $ip 存款ip
 * @property string $deleted_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Carrier $carrier
 * @property-read \App\Models\CarrierPayChannel $carrierPayChannel
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\PlayerBankCard $playerBankCard
 * @property-read \App\Models\CarrierActivity $relatedCarrierActivity
 * @property-read \App\Models\CarrierUser $reviewUser
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog between($start_time, $end_time)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog byFinishTimeRange($startTime, $endTime)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog byPlayerId($playerId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog orderByFinishTime($orderType)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog payedSuccessfully()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog retrieveByOrderNumber($orderNumber)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerDepositPayLog waitingReview()
 *         @mixin \Eloquent
 */
class PlayerDepositPayLog extends BaseDepositModel
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
    }

    public static $prefix = 'ply';

    public $table = 'log_player_deposit_pay';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_activity_id',
        'carrier_id',
        'player_id',
        'pay_order_number',
        'carrier_pay_channel',
        'amount',
        'finally_amount',
        'benefit_amount',
        'fee_amount',
        'is_fee_amt',
        'bonus_amount',
        'withdraw_flow_limit_amount',
        'pay_channel',
        'status',
        'operate_time',
        'credential',
        'remark',
        'bank_no',
        'pay_type',
        'ip'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'player_id' => 'integer',
        'pay_channel' => 'integer',
        'credential' => 'string',
        'remark' => 'string',
        'carrier_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public static $requestAttributes = [
        'amount' => '金额'
    ];

    public static function createRules()
    {
        return [
            'amount' => 'required|numeric|min:0'
        ];
    }

    /**
     * 订单是否能够支付
     *
     * @return bool
     */
    public function canPay()
    {
        if ($this->player->isActive() == false || $this->player->isLocked() == true) {
            return false;
        }
        return $this->status == self::ORDER_STATUS_CREATED;
    }

    /**
     * 订单是否支付成功
     *
     * @return bool
     */
    public function isPayedSuccessfully()
    {
        if ($this->player->isActive() == false || $this->player->isLocked() == true) {
            return false;
        }
        return $this->status == self::ORDER_STATUS_PAY_SUCCEED || $this->status == self::ORDER_STATUS_WAITING_REVIEW;
    }

    /**
     * *根据玩家id查询
     *
     * @param Builder $query
     * @param
     *            $playerId
     * @return $this
     */
    public function scopeByPlayerId(Builder $query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeWaitingReview(Builder $query)
    {
        return $query->where('status', 2);
    }

    /**
     * 订单是否能够审核
     *
     * @return bool
     */
    public function canReview()
    {
        if ($this->player->isActive() == true && $this->player->isLocked() == true &&
             $this->status == self::ORDER_STATUS_WAITING_REVIEW) {
            return false;
        }
        return true;
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public function carrierPayChannel()
    {
        return $this->belongsTo(CarrierPayChannel::class, 'carrier_pay_channel', 'id');
    }

    public function reviewUser()
    {
        return $this->hasOne(CarrierUser::class, 'id', 'review_user_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }

    public function playerBankCard()
    {
        return $this->belongsTo(PlayerBankCard::class, 'player_bank_card', 'card_id');
    }

    public function relatedCarrierActivity()
    {
        return $this->hasOne(CarrierActivity::class, 'id', 'carrier_activity_id');
    }
}
