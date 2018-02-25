<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/21
 * Time: 下午3:41
 */

namespace App\Vendor\Pay\Gateway;


use App\Models\Log\PlayerDepositPayLog;

class PayOrderFetchResponse
{

    /**
     *在线支付跳转
     */
    const WEB_PAY_TYPE_REDIRECT = 'REDIRECT';
    /**
     *微信扫一扫
     */
    const WEB_PAY_TYPE_WECHAT_SCAN = 'WECHAT_SCAN';
    /**
     *支付宝扫一扫
     */
    const WEB_PAY_TYPE_ALIPAY_SCAN = 'ALIPAY_SCAN';
    /**
     *线下转账
     */
    const WEB_PAY_TYPE_OFF_LINE_TRANSFER = 'OFF_LINE_TRANSFER';

    /**
     * @var PlayerDepositPayLog
     */
    public $payOrder;

    public $payType;

    public $payUrl;

}