<?php
namespace App\Models\Log;

use App\Models\CarrierPayChannel;
use App\Scopes\CarrierScope;
use Eloquent as Model;

/**
 * Class CarrierQuotaConsumptionLog
 *
 * @package App\Models\Log
 * @version April 4, 2017, 1:47 pm CST
 * @property int $log_id
 * @property int $carrier_id 运营商ID
 * @property float $amount 操作金额
 * @property float $pay_channel_remain_amount 余额
 * @property int $related_pay_channel 交易支付渠道
 * @property string $consumption_source 消费来源
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at 操作时间
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at @mixin \Eloquent
 * @property-read \App\Models\CarrierPayChannel $carrierPayChannel
 */
class CarrierQuotaConsumptionLog extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_carrier_quota_consumption';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'log_id';

    public $fillable = [
        'carrier_id',
        'amount',
        'pay_channel_remain_amount',
        'related_pay_channel',
        'consumption_source',
        'remark'
    ];

    public function carrierPayChannel()
    {
        return $this->belongsTo(CarrierPayChannel::class, 'related_pay_channel', 'id');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'log_id' => 'integer',
        'carrier_id' => 'integer',
        'related_pay_channel' => 'integer',
        'consumption_source' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public static function createLog($carrierId, $amount, $consumption_source, $remark)
    {
        $consumptionLog = array(
            'carrier_id' => $carrierId,
            'amount' => $amount,
            'pay_channel_remain_amount' => 0,
            'consumption_source' => $consumption_source,
            'remark' => $remark
        );
        self::create($consumptionLog);
    }
}
