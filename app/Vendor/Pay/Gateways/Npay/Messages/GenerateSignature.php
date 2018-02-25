<?php
namespace App\Vendor\Pay\Gateways\Npay\Messages;

trait GenerateSignature
{

    private function signNpay($params, $key)
    {
        ksort($params);
        $uri = urldecode(http_build_query($params));
        $uri = $uri . $key;
        $result = base64_encode(md5($uri, TRUE));
        return $result;
    }

    private function base64Npay($params, $decode = true)
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
}