<?php
namespace App\Models\Log;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Models\CarrierPayChannel;
use App\Models\CarrierUser;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Notifications\CarrierPlayerWithdrawNotification;
use App\Scopes\CarrierScope;
use App\Scopes\PlayerScope;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Log\PlayerWithdrawLog
 *
 * @property int $id
 * @property string $order_number 取款流水单号
 * @property int $carrier_id
 * @property int $player_id
 * @property float $apply_amount 申请金额
 * @property float $fee_amount 手续费
 * @property float $finally_withdraw_amount 实取金额
 * @property int $carrier_pay_channel 运营商出款支付渠道
 * @property int $player_bank_card 用户入款银行
 * @property int $status 0 待审核 -1 拒绝 1 出款 2 成功
 * @property string $reviewed_at 审核时间
 * @property string $withdraw_succeed_at 出款时间
 * @property \App\Models\CarrierUser $operator 审核人
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\PlayerBankCard $bankCard
 * @property-read \App\Models\Carrier $carrier
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawLog reviewed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawLog unReviewed()
 *         @mixin \Eloquent
 * @property-read \App\Models\CarrierUser $carrierOperator
 * @property-read \App\Models\CarrierPayChannel $carrierPayChannel
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawLog accountOut()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawLog byPlayerId($playerId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerWithdrawLog withdrawSucceedAtToday()
 */
class PlayerWithdrawLog extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
        self::created(function (PlayerWithdrawLog $log) {
            if ($log->status = PlayerWithdrawLog::STATUS_WAITING_REVIEWED) {
                // 通知当前取款用户的运营商;
                CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($log->carrier_id)->notify(new CarrierPlayerWithdrawNotification($log));
            }
        });
    }

    public $table = 'log_player_withdraw';

    const STATUS_WAITING_REVIEWED = - 2;

    const STATUS_REFUSED = - 1;

    const STATUS_PAYED_OUT = 1;

    // const STATUS_SUCCEED_REVIEWED = 2;
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'order_number',
        'carrier_id',
        'player_id',
        'apply_amount',
        'finally_withdraw_amount',
        'carrier_pay_channel',
        'player_bank_card',
        'status',
        'reviewed_at',
        'withdraw_succeed_at',
        'operator',
        'remark'
    ];

    protected $casts = [
        'id' => 'integer',
        'order_number' => 'string',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'carrier_pay_channel' => 'integer',
        'player_bank_card' => 'integer',
        'status' => 'integer',
        'operator' => 'integer',
        'remark' => 'string'
    ];
    public static $requestAttributes = [
        'status' => '状态',
        'carrier_pay_channel' => '出款银行',
        'fee_bear_side' => '手续费承担方',
        'fee_amount' => '出款手续费',
    ];
    /**
     * 生成取款流水单号
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        \DB::beginTransaction();
        do {
            $payNumber = substr(time() . rand(100000, 999999), 0, 15);
        } while (self::where('order_number', $payNumber)->lockForUpdate()->count() > 0);
        \DB::commit();
        return $payNumber;
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'remark' => 'max:255'
    ];

    public static function statusMeta()
    {
        return [
            self::STATUS_WAITING_REVIEWED => '待审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_PAYED_OUT => '已出款'
        ];
    }

    public static function statusMetaS()
    {
        return [
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_PAYED_OUT => '已出款'
        ];
    }

    public function scopeUnReviewed(Builder $query)
    {
        return $query->where('status', self::STATUS_WAITING_REVIEWED);
    }

    public function scopeReviewed(Builder $query)
    {
        return $query->where('status', '!=', self::STATUS_WAITING_REVIEWED);
    }

    // 查询状态为出账
    public function scopeAccountOut(Builder $query)
    {
        return $query->where('status', '=', self::STATUS_PAYED_OUT);
    }

    public function scopeByPlayerId(Builder $query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeWithdrawSucceedAtToday(Builder $query)
    {
        return $query->whereBetween('withdraw_succeed_at', [
            Carbon::now()->startOfDay()
                ->toDateTimeString(),
            Carbon::now()->endOfDay()
                ->toDateTimeString()
        ]);
    }

    public function scopeWithdrawSucceedBetween(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('withdraw_succeed_at', [
            $startTime,
            $endTime
        ]);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function carrierOperator()
    {
        return $this->belongsTo(CarrierUser::class, 'operator', 'id');
    }

    public function carrierPayChannel()
    {
        return $this->belongsTo(CarrierPayChannel::class, 'carrier_pay_channel', 'id');
    }

    public function bankCard()
    {
        return $this->belongsTo(PlayerBankCard::class, 'player_bank_card', 'card_id');
    }
}
