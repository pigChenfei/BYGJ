<?php
namespace App\Vendor\Pay\Gateways\Apipay;

use App\Vendor\Pay\Gateways\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public $data_map = [
        'p0_Cmd' => 'const:Buy',
        'p1_MerId' => 'thirdPart:merchant_number',
        'p2_Order' => 'pay_order_number', // 订单id
        'p3_Amt' => 'amount',
        'p4_Cur' => 'const:CNY', // 币种
        'p5_Pid' => 'thirdPart:good_name', // 商品名称
        'p6_Pcat' => 'config:p6_Pcat', // 商品种类
        'p7_Pdesc' => 'config:p7_Pdesc',
        // 'frontUrl' => 'attribute:returnURL', // 前端返回地址
        'p8_Url' => 'attribute:webhookURL', // 后端通知地址
        'p9_SAF' => 'const:0', // 为"1": 需要用户将送货地址留在API支付系统;为"0": 不需要，默认为 "0".
        'pa_MP' => 'config:pa_mp', // 商户扩展信息
        'pd_FrpId' => 'bank_no',
        'pd_FrpId' => 'const:1' // 默认为"1": 需要应答机制;
    ];

    public $status_map = 'respCode';
}