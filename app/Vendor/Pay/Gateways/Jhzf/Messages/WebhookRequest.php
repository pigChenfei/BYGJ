<?php
namespace App\Vendor\Pay\Gateways\Jhzf\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookRequest;

class WebhookRequest extends AbstractWebhookRequest
{
    use GenerateSignature;

    public $statusCode = 'paying';

    public $statusKey = 'transactionResult';

    public $orderNumKey = 'merchantOrderNo';

    public function handle()
    {
        $input = $this->getRequestData();
        if (empty($this->model)) {
            $this->model =\PlayerDepositPayLog::where('pay_order_number',$input['merchantOrderNo'])->first();
            if(empty($this->model))
            {
                \Log::info('订单不存在');
            }
            return;
        }
        $public_key = file_get_contents(__DIR__.'/public.pem');
        $public_key = openssl_get_publickey($public_key);
        $Url=urldecode($_SERVER['QUERY_STRING']);
        $Yzsign=$_GET['sign'];
        $NewUrl="";
        $arr = explode("&", $Url);
        sort($arr);
        foreach ($arr as $val )
        {
            if(explode("=",$val)[0]!="sign" ){
                if(explode("=",$val)[0]!="customizeParam")
                {
                    $NewUrl=$NewUrl.$val.'&';
                }
            }
        }
        $NewUrl=substr($NewUrl,0,strlen($NewUrl)-1);
       // if ($this->rsaVerify($NewUrl,$Yzsign,$public_key)) {
            $response = parent::handle();
            \WLog::info('========= ========    jhzf 通知处理成功   ========= ========');
            return $response;
      //  } else {
       //     \WLog::error('========= ========       jhzf 通知签名错误     ========= ========');
       // }
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