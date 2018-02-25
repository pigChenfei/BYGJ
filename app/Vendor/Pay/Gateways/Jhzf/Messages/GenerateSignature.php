<?php
namespace App\Vendor\Pay\Gateways\Jhzf\Messages;

trait GenerateSignature
{
   public function rsaSign($params) 
   {
        $private_key = file_get_contents(__DIR__.'/private.pem');
        $res = openssl_pkey_get_private($private_key);
        $data = "";
        foreach ($params as $key => $param) {
            $data=$data.$key.'='.$param.'&';
        }
        $data =rtrim($data,'&');
        \WLog::info('字符串',$data);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    public function rsaVerify($prestr, $sign,$pkeyid) 
    {
        $sign = base64_decode($sign);
        if ($pkeyid) {
            $verify = openssl_verify($prestr, $sign, $pkeyid);
            openssl_free_key($pkeyid);
        }
        if($verify == 1){
            return true;
        }else{
            return false;
        }
    }
}