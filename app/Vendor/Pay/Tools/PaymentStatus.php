<?php
namespace App\Vendor\Pay\Tools;

/**
 * 订单状态表
 *
 * @author joker
 *
 */
class PaymentStatus
{

    /**
     * 在线订单支付成功
     *
     * @var integer
     */
    const COMPLETED = 1;

    /**
     * 在线订单支付失败
     *
     * @var unknown
     */
    const FAILED = - 1;

    /**
     * 订单创建成功，待支付
     *
     * @var integer
     */
    const CREATED = 0;

    /**
     * 订单审核失败
     *
     * @var unknown
     */
    const VERIFY_FAILED = - 2;

    /**
     * 订单审核成功
     *
     * @var integer
     */
    const VERIFY_SUCCESS = 2;
}

