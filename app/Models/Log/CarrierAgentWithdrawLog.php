<?php
namespace App\Models\Log;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Carrier;

/**
 * Class CarrierAgentWithdrawLog
 *
 * @package App\Models\Carrier
 * @version April 25, 2017, 1:58 pm CST
 */
class CarrierAgentWithdrawLog extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_agent_withdraw';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const STATUS_WAITING_REVIEWED = - 2;

    const STATUS_REFUSED = - 1;

    const STATUS_PAYED_OUT = 1;

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'order_number',
        'carrier_id',
        'agent_id',
        'apply_amount',
        'fee_amount',
        'finally_withdraw_amount',
        'carrier_pay_channel',
        'player_bank_card',
        'status',
        'reviewed_at',
        'withdraw_succeed_at',
        'operator',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_number' => 'string',
        'carrier_id' => 'integer',
        'agent_id' => 'integer',
        'carrier_pay_channel' => 'integer',
        'player_bank_card' => 'integer',
        'operator' => 'integer',
        'remark' => 'string'
    ];

    /**
     * 生成取款流水单号
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        // TODO 考虑到并发场景 后续计划使用锁表或redis解决
        do {
            $payNumber = substr(time() . rand(100000, 999999), 0, 15);
        } while (self::where('order_number', $payNumber)->count() > 0);
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
    public function agent()
    {
        return $this->belongsTo(\App\Models\CarrierAgentUser::class, 'agent_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function carrierOperator()
    {
        return $this->belongsTo(\App\Models\CarrierUser::class, 'operator', 'id');
    }

    public function carrierPayChannel()
    {
        return $this->belongsTo(\App\Models\CarrierPayChannel::class, 'carrier_pay_channel', 'id');
    }

    public function bankCard()
    {
        return $this->belongsTo(\App\Models\AgentBankCard::class, 'player_bank_card', 'id');
    }
}
