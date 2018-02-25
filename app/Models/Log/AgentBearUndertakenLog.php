<?php
namespace App\Models\Log;

use App\Models\CarrierAgentUser;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AgentBearUndertakenLog
 *
 * @package App\Models\Log
 * @version April 12, 2017, 8:15 pm CST
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property float $amount 金额
 * @property float $company_amount
 * @property int $agent_id 代理用户ID
 * @property bool $is_settled 是否已计算 0未结算，1已结算
 * @property string $settled_at 结算时间
 * @property bool $undertaken_type 承担类型 1:代理优惠存款承担 2:洗码承担 3:红利承担 4:取款手续费承担 5:存款手续费承担
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\CarrierAgentUser $agent @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentBearUndertakenLog isSettled($isSettled)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\AgentBearUndertakenLog undertakeType($type)
 */
class AgentBearUndertakenLog extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    // 代理存款优惠承担
    const UNDERTAKEN_TYPE_DEPOSIT_BENEFIT = 1;

    // 洗码承担
    const UNDERTAKEN_TYPE_BET_FINANCIAL_FLOW = 2;

    // 红利承担
    const UNDERTAKEN_TYPE_BONUS = 3;

    // 存款手续费
    const UNDERTAKEN_TYPE_DEPOSIT_FEE = 4;

    // 取款手续费
    const UNDERTAKEN_TYPE_WITHDRAW_FEE = 5;

    public $table = 'log_agent_undertaken';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'amount',
        'company_amount',
        'agent_id',
        'log_agent_settled_id',
        'is_settled',
        'settled_at',
        'undertaken_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'amount' => 'float',
        'company_amount' => 'float',
        'agent_id' => 'integer',
        'is_settled' => 'boolean',
        'undertaken_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function agent()
    {
        return $this->belongsTo(CarrierAgentUser::class, 'agent_id', 'id');
    }

    public function scopeByAgentUser(Builder $query, $agentUserId)
    {
        return $query->where('agent_id', $agentUserId);
    }

    public function scopeIsSettled(Builder $query, $isSettled)
    {
        return $query->where('is_settled', $isSettled);
    }

    public function scopeUndertakeType(Builder $query, $type)
    {
        return $query->where('undertaken_type', $type);
    }
}
