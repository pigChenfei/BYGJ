<?php

namespace App\Models\Log;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Notifications\CarrierAgentWithdrawNotification;
use Eloquent as Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AgentWithdrawLog
 * @package App\Models\Carrier
 * @version April 19, 2017, 3:38 pm CST
 */
class AgentWithdrawLog extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        self::created(function (AgentWithdrawLog $log) {
            if ($log->status = AgentWithdrawLog::STATUS_WAITING_REVIEWED) {
                // 通知当前取款用户的运营商;
                CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($log->carrier_id)->notify(new CarrierAgentWithdrawNotification($log));
            }
        });
    }

    public $table = 'log_agent_withdraw';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_WAITING_REVIEWED = -2;
    const STATUS_REFUSED = -1;
    const STATUS_PAYED_OUT = 1;

    protected $dates = ['deleted_at'];


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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static function statusMeta(){
        return [
            self::STATUS_WAITING_REVIEWED => '待审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_PAYED_OUT => '已出款',
        ];
    }

    /**
     * 生成取款流水单号
     * @return string
     */
    public static function generateOrderNumber(){
        //TODO 考虑到并发场景 后续计划使用锁表或redis解决
        do{
            $payNumber = substr(time().rand(100000,999999),0,15);
        }while(self::where('order_number',$payNumber)->count() > 0);
        return $payNumber;
    }

    //查询状态为出账
    public function scopeAccountOut(Builder $query){
        return $query->where('status','=',self::STATUS_PAYED_OUT);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function carrierUser()
    {
        return $this->belongsTo(\App\Models\CarrierUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function carrier()
    {
        return $this->belongsTo(\App\Models\Carrier::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agentUser()
    {
        return $this->belongsTo(\App\Models\CarrierAgentUser::class);
    }
}
