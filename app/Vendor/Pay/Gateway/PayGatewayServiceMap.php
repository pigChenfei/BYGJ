<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/21
 * Time: 下午4:08
 */
namespace App\Vendor\Pay\Gateway;

use App\Vendor\Pay\OfflineDeposit\OfflineDepositOrderGateway;
use App\Vendor\Pay\OnlineDeposit\OnlineDepositOrderGateway;
use App\Vendor\Pay\Zhifu\ZhifuPayOrderGateway;
use App\Vendor\Pay\Guofubao\GuofubaoPayOrderGateway;

class PayGatewayServiceMap
{

    const GATEWAY_OFFLINE_DEPOSIT = 'GATEWAY_OFFLINE_DEPOSIT';

    /**
     *
     * @var array
     */
    public static $payServiceMap = [
        'ZHIFU' => ZhifuPayOrderGateway::class,
        // 'GUOFUBAO' => GuofubaoPayOrderGateway::class,
        'GUOFUBAO' => OnlineDepositOrderGateway::class,
        'WANGYINXIN' => OnlineDepositOrderGateway::class,
        'JSYH' => OnlineDepositOrderGateway::class,
        'NPAY' => OnlineDepositOrderGateway::class,
        'APIPAY' => OnlineDepositOrderGateway::class,
        'JHZF'=>OnlineDepositOrderGateway::class,
        self::GATEWAY_OFFLINE_DEPOSIT => OfflineDepositOrderGateway::class
    ];
}