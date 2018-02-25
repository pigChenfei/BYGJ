<?php

namespace App\Models\Log;

use App\Models\Log\Base\BaseDepositModel;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class CarrierAgentDepositPayLog
 * @package App\Models\Carrier
 * @version April 25, 2017, 1:20 pm CST
 */
class CarrierAgentDepositPayLog extends BaseDepositModel
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    
    public $table = 'log_agent_deposit_pay';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'pay_order_number',
        'carrier_id',
        'pay_order_channel_trade_number',
        'agent_id',
        'amount',
        'finally_amount',
        'benefit_amount',
        'bonus_amount',
        'fee_amount',
        'pay_channel',
        'status',
        'review_user_id',
        'operate_time',
        'credential',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pay_order_number' => 'string',
        'carrier_id' => 'integer',
        'pay_order_channel_trade_number' => 'string',
        'agent_id' => 'integer',
        'pay_channel' => 'integer',
        'review_user_id' => 'integer',
        'credential' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static $requestAttributes = [
        'amount' => '金额'
    ];

    public static function createRules(){
        return [
            'amount' => 'required|numeric|min:0'
        ];
    }

    /**
     * 订单是否能够支付
     * @return bool
     */
    public function canPay(){
        if($this->player->isActive() == false || $this->player->isLocked() == true){
            return false;
        }
        return $this->status == self::ORDER_STATUS_CREATED;
    }

    /**
     * 订单是否支付成功
     * @return bool
     */
    public function isPayedSuccessfully(){
        if($this->player->isActive() == false || $this->player->isLocked() == true){
            return false;
        }
        return $this->status == self::ORDER_STATUS_PAY_SUCCEED || $this->status == self::ORDER_STATUS_WAITING_REVIEW;
    }

    /***根据玩家id查询
     * @param Builder $query
     * @param $playerId
     * @return $this
     */
    public function scopeByPlayerId(Builder $query, $playerId){
        return $query->where('player_id',$playerId);
    }

    /**
     * 生成订单号
     * @return string
     * @throws \Exception
     */
    public static function generatePayNumber(){
        try{
            DB::beginTransaction();
            do{
                $payNumber = time().rand(100000,999999);
                //悲观锁应用
            }while(PlayerDepositPayLog::lockForUpdate()->where('pay_order_number',$payNumber)->count() > 0);
            DB::commit();
            return $payNumber;
        }catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * 生成凭证
     * @return string
     * @throws \Exception
     */
    public static function generateCredential(){
        try{
            DB::beginTransaction();
            do{
                $credential = substr(md5(time().rand(100000,999999)),0,6);
            }while(PlayerDepositPayLog::lockForUpdate()->where('credential',$credential)->count() > 0);
            DB::commit();
            return $credential;
        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * 订单是否能够审核
     * @return bool
     */
    public function canReview(){
        if($this->player->isActive() == false || $this->player->isLocked() == true){
            return false;
        }
        return $this->status == self::ORDER_STATUS_WAITING_REVIEW;
    }
    
    public function carrierPayChannel(){
        return $this->belongsTo(\App\Models\CarrierPayChannel::class,'carrier_pay_channel','id');
    }
    
    public function reviewUser(){
        return $this->hasOne(\App\Models\CarrierUser::class,'id','review_user_id');
    }
    
    public function agent(){
        return $this->belongsTo(\App\Models\CarrierAgentUser::class,'id','agent_id');
    }

}
