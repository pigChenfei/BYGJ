<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2018/1/2
 * Time: 21:28
 */

namespace App\Vendor\Pay\Gateways\Wangyinxin;


use App\Vendor\Pay\Gateways\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public $data_map = [
        'MerNo' => 'thirdPart:merchant_number',     //商户编号
        'BillNo' => 'pay_order_number',     //
        'Amount' => 'amount',
        'Currency' => 'const:1',
        'PayType' => 'const:1',
        'ReturnURL' => 'attribute:returnURL',
        'NotifyURL' => 'attribute:webhookURL',
        'GoodsSubject' => 'const:一次性购物',
        'GoodsDescription' => 'const:只购物一次',
        'MD5key' => 'thirdPart:merchant_number'
    ];
}