<?php
// 支付网关配置参数
return [
    'guofubao' => [
        'version' => '2.2',
        'language' => '1',
        'charset' => '1',
        'url' => [
            'create' => 'https://gatewaymer.gopay.com.cn/Trans/WebClientAction.do',
            'query' => ''
        ]
    ],
    'wangyinxin' => [
        'url' => [
            'create' => 'http://121.201.38.37:8076/api/payment.aspx'
        ]
    ],
    'npay' => [
        'subject' => '商品标题',
        'body' => '商品内容描述',
        'userId' => '',
        'merResv1' => '',
        'url' => [
            'create' => 'http://epay-testing.nongfupay.com/pay'
        ]
    ],
    'apipay' => [
        'p6_Pcat' => '商品分类',
        'p7_Pdesc' => '商品内容描述',
        'pa_mp' => '',
        'url' => [
            'create' => 'http://www.126473.com/GateWay/ReceiveBank.aspx'
        ]
    ]
];