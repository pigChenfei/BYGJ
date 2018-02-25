<?php
namespace App\Models;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Jobs\ProcessPlayerAutoAuditActivity;
use App\Notifications\CarrierPlayerJoinActivityNotification;
use App\Scopes\PlayerScope;
use Carbon\Carbon;
use Eloquent as Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CarrierActivityAudit
 *
 * @package App\Models\Carrier
 * @version April 8, 2017, 4:07 pm CST
 * @property int $id
 * @property int $act_id 活动ID
 * @property int $carrier_id 运营商ID
 * @property int $player_id 玩家ID
 * @property bool $status 状态 1待审核 2通过 -1拒绝
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $remark
 * @property-read \App\Models\CarrierActivity $activity
 * @property-read \App\Models\Player $player @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit byActivity($activityId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit joinedThisMonth()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit joinedThisWeek()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit joinedToday()
 * @property float $process_deposit_amount 处理存款金额
 * @property float $process_bonus_amount 处理红利金额
 * @property float $process_withdraw_flow_limit 处理取款流水
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit hasAudited()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivityAudit waitingAudit()
 */
class CarrierActivityAudit extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::addGlobalScope(new PlayerScope());
        self::created(function (CarrierActivityAudit $activityAudit) {
            if ($activityAudit->status == CarrierActivityAudit::STATUS_AUDIT) {
                $carrierActivity = $activityAudit->activity;
                // 手动审核才发送通知
                if ($carrierActivity->censor_way == CarrierActivity::CENSOR_WAY_MANUAL) {
                    CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($activityAudit->carrier_id)->notify(new CarrierPlayerJoinActivityNotification($carrierActivity));
                }
                // 如果是自动审核,则加入审核队列中
                // if($carrierActivity->censor_way == CarrierActivity::CENSOR_WAY_ACCORD){
                // dispatch(new ProcessPlayerAutoAuditActivity($activityAudit));
                // }
            }
        });
    }

    public $table = 'inf_carrier_activity_audit';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    /**
     * 待审核
     *
     * @var integer
     */
    const STATUS_AUDIT = 1;

    /**
     * 通过
     *
     * @var integer adopt
     */
    const STATUS_ADOPT = 2;

    /**
     * 拒绝
     *
     * @var integer refuse
     */
    const STATUS_REFUSE = - 1;

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'act_id',
        'carrier_id',
        'status',
        'ip'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'act_id' => 'integer',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'ip' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    // 会员
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    /**
     * 优惠活动
     *
     * @return type
     */
    public function activity()
    {
        return $this->belongsTo(CarrierActivity::class, 'act_id', 'id');
    }

    public function scopeHasAudited(Builder $query)
    {
        return $query->where('status', '!=', self::STATUS_AUDIT);
    }

    public function scopeWaitingAudit(Builder $query)
    {
        return $query->where('status', self::STATUS_AUDIT);
    }

    public static function statusMeta()
    {
        return [
            self::STATUS_AUDIT => '待审核',
            self::STATUS_ADOPT => '通过',
            self::STATUS_REFUSE => '拒绝'
        ];
    }

    public function scopeByActivity(Builder $query, $activityId)
    {
        return $query->where('act_id', $activityId);
    }

    public function scopeJoinedToday(Builder $query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfDay()
                ->toDateTimeString(),
            Carbon::now()->toDateTimeString()
        ]);
    }

    public function scopeJoinedThisWeek(Builder $query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek()
                ->toDateTimeString(),
            Carbon::now()->toDateTimeString()
        ]);
    }

    public function scopeJoinedThisMonth(Builder $query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth()
                ->toDateTimeString(),
            Carbon::now()->toDateTimeString()
        ]);
    }

    public function scopeByPlayer(Builder $query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }
}
