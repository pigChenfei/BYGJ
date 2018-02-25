<?php
namespace App\Models\Log\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseDepositModel extends Model
{

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public static $prefix = null;

    /**
     * 订单已创建
     */
    const ORDER_STATUS_CREATED = 0;

    /**
     * 订单支付成功
     */
    const ORDER_STATUS_PAY_SUCCEED = 1;

    /**
     * 订单待审核
     */
    const ORDER_STATUS_WAITING_REVIEW = 2;

    /**
     * 订单支付失败
     */
    const ORDER_STATUS_PAY_FAILED = - 1;

    /**
     * 订单未审核通过
     */
    const ORDER_STATUS_SERVER_REVIEW_NO_PASSED = - 2;

    public static function isPlayerDepositOrder($pay_order_number)
    {
        return ('ply' == substr($pay_order_number, 0, 3));
    }

    public static function onlineTransferType()
    {
        return [
            1 => 'ATM存款',
            2 => 'ATM转账',
            3 => 'ATM跨行转账',
            4 => '银行汇款',
            5 => '跨行汇款',
            6 => '网银转账',
            7 => '网银跨行转账',
            8 => '支付宝转账'
        
        ];
    }

    public static $requestAttributes = [
        'amount' => '金额'
    ];

    public static function createRules()
    {
        return [
            'amount' => 'required|numeric|min:0'
        ];
    }

    public static function orderStatusMeta()
    {
        return [
            self::ORDER_STATUS_CREATED => '待支付',
            self::ORDER_STATUS_PAY_SUCCEED => '支付成功',
            self::ORDER_STATUS_PAY_FAILED => '支付失败',
            self::ORDER_STATUS_WAITING_REVIEW => '待审核',
            self::ORDER_STATUS_SERVER_REVIEW_NO_PASSED => '审核未通过'
        ];
    }

    /**
     * *订单是否能够支付
     *
     * @return mixed
     */
    public abstract function canPay();

    /**
     * *是否支付成功
     *
     * @return mixed
     */
    public abstract function isPayedSuccessfully();

    /**
     * 根据订单号查询订单
     *
     * @param Builder $query
     * @param $orderNumber
     * @return Builder
     */
    public function scopeRetrieveByOrderNumber(Builder $query, $orderNumber)
    {
        return $query->where('pay_order_number', $orderNumber);
    }

    public function scopePayedSuccessfully(Builder $query)
    {
        return $query->where('status', self::ORDER_STATUS_PAY_SUCCEED);
    }

    public function scopeWaitingReview(Builder $query)
    {
        return $query->where('status', self::ORDER_STATUS_WAITING_REVIEW);
    }

    public function scopeByFinishTimeRange(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('operate_time', [
            $startTime,
            $endTime
        ]);
    }

    public function scopeByFinishUpdateTimeRange(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('updated_at', [
            $startTime,
            $endTime
        ]);
    }

    public function scopeOrderByFinishTime(Builder $query, $orderType)
    {
        return $query->orderBy('operate_time', $orderType);
    }

    public function scopeBetween(Builder $query, $start_time, $end_time)
    {
        return $query->whereBetween('created_at', [
            $start_time,
            $end_time
        ]);
    }

    /**
     * 生成订单号
     *
     * @return string
     * @throws \Exception
     */
    public static function generatePayNumber()
    {
        try {
            $deposit_pay_log = static::class;
            
            DB::beginTransaction();
            do {
                $payNumber = $deposit_pay_log::$prefix . '_' . time() . rand(100000, 999999);
                // 悲观锁应用
            } while ($deposit_pay_log::lockForUpdate()->where('pay_order_number', $payNumber)->count() > 0);
            DB::commit();
            return $payNumber;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function generateCredential()
    {
        $deposit_pay_log = static::class;
        try {
            DB::beginTransaction();
            do {
                $credential = substr(md5(time() . rand(100000, 999999)), 0, 6);
            } while ($deposit_pay_log::lockForUpdate()->where('credential', $credential)->count() > 0);
            DB::commit();
            return $credential;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 订单是否能够审核
     *
     * @return bool
     */
    public abstract function canReview();
}
