<?php

// echo urlencode('5DKXc8yYZitNKDZwdh0zfA==');
// exit();
function npay_sign($params, $key)
{
    ksort($params);
    $uri = urldecode(http_build_query($params));
    $uri = $uri . $key;
    $result = base64_encode(md5($uri, TRUE));
    return $result;
}

function npay_base64($params, $decode = true)
{
    $need_base64_fields = [
        'subject',
        'body'
    ];
    foreach ($need_base64_fields as $k) {
        if (array_key_exists($k, $params)) {
            if ($decode) {
                $params[$k] = base64_decode($params[$k]);
            } else {
                $params[$k] = base64_encode($params[$k]);
            }
        }
    }
    return $params;
}

$a = array(
    'merchantId' => '600000000000002',
    'merOrderId' => 'ply_1516098211932491',
    'txnAmt' => '100',
    'respCode' => '1001',
    'respMsg' => '交易成功'
    // 'signature' => 'Zk0LFna4r8gAh9UcF2hiDw==',
    // 'merchantid' => '600000000000002',
    // 'merorderid' => 'ply_1516098211932491',
    // 'respcode' => 10001,
    // 'respmsg' => '%e4%ba%a4%e6%98%93%e6%88%90%e5%8a%9'
    // 'frontUrl' => 'http://ttc.bet/player.pay/return/npay/ply_1516098211932491',
    // 'bankId' => '01030000',
    // 'subject' => '5ZWG5ZOB5qCH6aKY',
    // 'body' => '5ZWG5ZOB5YaF5a655o+P6L+w',
    // 'userId' => '',
    // 'merResv1' => '',
    // 'gateway' => 'bank',
    // 'dcType' => 0
    // 'signMethod' => 'MD5',
    // 'signature' => '5DKXc8yYZitNKDZwdh0zfA=='
);
// // 秘钥
$key = '682807c82e2716452bd069aaf72d48dc';
$paramsNeedSign = npay_base64($a, true);
$signature = npay_sign($paramsNeedSign, $key);

echo $signature;
exit();

// // 秘钥
// $key = '682807c82e2716452bd069aaf72d48dc';

// // 请求地址
// $url = 'http://epay-testing.nongfupay.com/pay';

// // 参数
// $params['merchantId'] = '600000000000002';
// $params['merOrderId'] = date('ymdhis');
// $params['txnAmt'] = '10000';
// $params['backUrl'] = 'http://a.ttcbet.com/postback/npay/ply_1515682876616435';
// $params['frontUrl'] = 'http://a.ttcbet.com/player.pay/return/npay/ply_1515682876616435';
// $params['subject'] = base64_encode('商品标题dfj');
// $params['body'] = base64_encode('商品描述sdaf');
// $params['userId'] = '';
// $params['merResv1'] = '';

// // 支付方式
// $params['gateway'] = 'bank';
// $params['bankId'] = '01020000';
// $params['dcType'] = '0';

// // 加密
// $paramsNeedSign = npay_base64($params, true);
// $signature = npay_sign($paramsNeedSign, $key);
// $params['signMethod'] = 'MD5';
// $params['signature'] = $signature;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        html, body {
            height: 98%;
        }

        body {
            background-color: #FFFFFF;
        }

        body, td, div {
            font-family: verdana, arial, sans-serif;
        }
    </style>
</head>
<body onLoad="return process();">
<table cellpadding="0" width="100%" height="100%" cellspacing="0" style="border:1px solid #003366;">
    <tr>
        <td align="center" style="height:100%; vertical-align:middle;">
            <div style="margin:10px;padding:10px;">
                <form name="form" method="post" action="<?php
                
                echo $url;
                ?>">
                    <input type="hidden" name="merchantId" value="<?php
                    
                    echo $params['merchantId'];
                    ?>"/>
                    <input type="hidden" name="merOrderId" value="<?php
                    
                    echo $params['merOrderId'];
                    ?>"/>
                    <input type="hidden" name="txnAmt" value="<?php
                    
                    echo $params['txnAmt'];
                    ?>"/>
                    <input type="hidden" name="backUrl" value="<?php
                    
                    echo $params['backUrl'];
                    ?>"/>
                    <input type="hidden" name="frontUrl" value="<?php
                    
                    echo $params['frontUrl'];
                    ?>"/>
                    <input type="hidden" name="subject" value="<?php
                    
                    echo $params['subject'];
                    ?>"/>
                    <input type="hidden" name="body" value="<?php
                    
                    echo $params['body'];
                    ?>"/>
                    <input type="hidden" name="userId" value="<?php
                    
                    echo $params['userId'];
                    ?>"/>
                    <input type="hidden" name="merResv1" value="<?php
                    
                    echo $params['merResv1'];
                    ?>"/>
                    <input type="hidden" name="gateway" value="<?php
                    
                    echo $params['gateway'];
                    ?>"/>
                    <input type="hidden" name="bankId" value="<?php
                    
                    echo $params['bankId'];
                    ?>"/>
                    <input type="hidden" name="dcType" value="<?php
                    
                    echo $params['dcType'];
                    ?>"/>
                    <input type="hidden" name="signMethod" value="<?php
                    
                    echo $params['signMethod'];
                    ?>"/>
                    <input type="hidden" name="signature" value="<?php
                    
                    echo $params['signature'];
                    ?>"/>
                    <button type='submit'>提交请求</button>
                </form>
            </div>
        </td>
    </tr>
</table>
</body>
</html>