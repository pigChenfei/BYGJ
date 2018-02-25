<?php
namespace App\Models\Log;

use Eloquent as Model;
use App\Scopes\CarrierScope;
use App\Models\CarrierAgentUser;

/**
 * Class CarrierAgentCommissionSettleLog
 *
 * @package App\Models\Carrier
 * @version April 19, 2017, 8:48 pm CST
 */
class CarrierAgentSettleDetailLog extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_agent_settle_detail';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    /**
     * 初审
     * The first trial tatus
     *
     * @var type
     */
    const FIRST_TATUS_STATUS = 1;

    /**
     * 复审
     * review
     *
     * @var type
     */
    const REVIEW_STATUS = 2;

    /**
     * 结算完成
     * Settlement completed
     *
     * @var type
     */
    const SET_COMPLETED_STATUS = 3;

    /**
     * 上周
     * Last week
     */
    const LAST_WEEK = 1;

    /**
     * 上半月
     * first half of the month
     */
    const FIRST_HALF_MONTH = 2;

    /**
     * 上月
     * Last month
     */
    const LAST_MONTH = 3;

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'in_agent_id',
        'out_agent_id',
        'agent_settle_id',
        'commission_money',
        'commission_rate',
        'level'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_id' => 'integer',
        'agent_settle_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function inAgent()
    {
        return $this->belongsTo(CarrierAgentUser::class, 'in_agent_id', 'id');
    }
    public function outAgent()
    {
        return $this->belongsTo(CarrierAgentUser::class, 'out_agent_id', 'id');
    }

    public function settlePeriods()
    {
        return $this->belongsTo(CarrierAgentSettleLog::class, 'agent_settle_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function logAgentRebateFinancialFlows()
    {
        return $this->hasMany(\App\Models\LogAgentRebateFinancialFlow::class);
    }

    public static function statusMeta()
    {
        return [
            self::FIRST_TATUS_STATUS => '初审',
            self::REVIEW_STATUS => '复审',
            self::SET_COMPLETED_STATUS => '结算完成'
        ];
    }

    public static function settlePeriodsMeta()
    {
        return [
            self::LAST_WEEK => '上周',
            // self::FIRST_HALF_MONTH => '上半月',
            self::LAST_MONTH => '上月'
        ];
    }

    public static function lastSettlePeriodsId()
    {
        return self::max('id');
    }
}
