<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/1
 * Time: 19:45
 */
namespace App\Vendor\Pay\Gateways\Guofubao\Messages;

trait GenerateSignature
{

    public function generateSignature(array $params = [])
    {
        if (! empty($params)) {
            $params = json_decode(json_encode($params));
            $stringA = 'version=[' . $params->version . ']tranCode=[' . $params->tranCode . ']merchantID=[' .
                 $params->merchantID . ']merOrderNum=[' . $params->merOrderNum . ']tranAmt=[' . $params->tranAmt .
                 ']feeAmt=[' . $params->feeAmt . ']tranDateTime=[' . $params->tranDateTime . ']frontMerUrl=[' .
                 $params->frontMerUrl . ']backgroundMerUrl=[' . $params->backgroundMerUrl . ']orderId=[' .
                 $params->orderId . ']gopayOutOrderId=[' . $params->gopayOutOrderId . ']' . 'tranIP=[' . $params->tranIP .
                 ']respCode=[' . $params->respCode . ']gopayServerTime=[' . $this->gopayServerTime . ']VerficationCode=[' .
                 $$params->VerficationCode . ']';
        } else {
            $stringA = 'version=[' . $this->version . ']tranCode=[' . $this->tranCode . ']merchantID=[' .
                 $this->merchantID . ']merOrderNum=[' . $this->merOrderNum . ']tranAmt=[' . $this->tranAmt .
                 ']feeAmt=[]tranDateTime=[' . $this->tranDateTime . ']frontMerUrl=[' . $this->frontMerUrl .
                 ']backgroundMerUrl=[' . $this->backgroundMerUrl . ']orderId=[]gopayOutOrderId=[' .
                 $this->payOutOrderId() . ']' . 'tranIP=[' . $this->tranIP . ']respCode=[]gopayServerTime=[' .
                 $this->gopayServerTime . ']VerficationCode=[' .
                 $this->model->carrierPayChannel->bindedThirdPartGateway->merchant_identify_code . ']';
        }
        \Log::info('支付拼接字符串' . $stringA);
        
        return md5($stringA);
    }
}