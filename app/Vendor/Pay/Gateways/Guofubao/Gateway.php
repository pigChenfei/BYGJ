<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/31
 * Time: 23:48
 */
namespace App\Vendor\Pay\Gateways\Guofubao;

use App\Vendor\Pay\Gateways\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public $data_map = [
        'version' => 'config:version',
        'charset' => 'config:charset',
        'language' => 'config:language',
        'signType' => 'const:1',
        'tranCode' => 'const:8888', // 交易代码
        'merchantID' => 'thirdPart:merchant_number',
        'merOrderNum' => 'pay_order_number',
        'tranAmt' => 'amount', // 交易金额
        'currencyType' => 'const:156',
        'frontMerUrl' => 'attribute:returnURL', // 前端返回地址
        'backgroundMerUrl' => 'attribute:webhookURL', // 后端通知地址
        'tranDateTime' => 'attribute:transTime',
        'virCardNoIn' => 'thirdPart:vir_card_no_in', // 本域指卖家在国付宝平台开设的国付宝账户号
        'tranIP' => 'attribute:transIp',
        'gopayOutOrderId' => 'attribute:payOutOrderId',
        'bankCode' => 'bank_no', // 银行代码
        'userType' => 'const:1',
        'VerficationCode' => 'thirdPart:merchant_identify_code'
    ];
}