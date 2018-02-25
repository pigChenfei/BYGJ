<?php
namespace App\Vendor\Pay\Gateways\Apipay\Messages;

trait GenerateSignature
{

    public function getReqHmacString($params, $merchantKey)
    {
        $str = "";
        foreach ($params as $key => $param) {
            $str .= $param;
        }
        // logstr($param['p2_Order'], $str, $this->hmacMd5($str, $merchantKey));
        return $this->hmacMd5($str, $merchantKey);
    }

    public function hmacMd5($data, $key)
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)
        
        // 需要配置环境支持iconv，否则中文参数不能正常处理
        // $key = iconv("GB2312","UTF-8",$key);
        // $data = iconv("GB2312","UTF-8",$data);
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*", md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;
        
        return md5($k_opad . pack("H*", md5($k_ipad . $data)));
    }
}