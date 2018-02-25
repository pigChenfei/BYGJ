<?php
namespace App\Vendor\Pay\Gateways\Jhzf;

use App\Vendor\Pay\Gateways\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public $data_map = [
        'amount' => 'attribute:getamount',
        'appId' =>'config:appId',
        'callBackUrl'=>'attribute:returnURL',
        'channel'=>'const:yb_pc',
        'currency'=>'config:currency',
        'desc' => 'config:desc',
        'extra'=>'attribute:paymentModeCode',
        'orderNo' => 'pay_order_number',
        'orderTime' => 'attribute:getNowDate', // 创建时间
        'subject'=>'config:subject',
        'toAccountType' => 'const:D0'        // 商品种类
    ];

    public $status_map = 'respCode';
}