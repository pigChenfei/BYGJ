<?php
namespace App\Vendor\Pay\Gateways\Npay;

use App\Vendor\Pay\Gateways\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public $data_map = [
        'signMethod' => 'config:signMethod',
        'merchantId' => 'thirdPart:merchant_number',
        'merOrderId' => 'pay_order_number',
        'txnAmt' => 'amount',
        'frontUrl' => 'attribute:returnURL', // 前端返回地址
        'backUrl' => 'attribute:webhookURL', // 后端通知地址
        'bankId' => 'bank_no',
        'dcType' => 'attribute:dcType',
        'subject' => 'config:subject',
        'body' => 'config:body',
        'userId' => 'config:userId',
        'merResv1' => 'config:merResv1',
        'gateway' => 'pay_type'
    ];

    public $status_map = 'respCode';
}