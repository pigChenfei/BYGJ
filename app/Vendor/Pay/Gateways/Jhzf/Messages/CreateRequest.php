<?php
namespace App\Vendor\Pay\Gateways\Jhzf\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRequest;
use App\Vendor\Pay\Gateways\Common\Messages\RequestMethod;
use App\Models\CarrierPayChannel;

class CreateRequest extends AbstractRequest
{
    use GenerateSignature;

    public $method = RequestMethod::FORM_POST;

    public $is_redirect = true;

    public $parameters = [
        'amount',
        'appId',
        'callBackUrl',
        'channel',
        'currency',
        'desc',
        'extra',
        'orderNo',
        'orderTime',
        'subject',
        'toAccountType' 
    ];

    public function sendData($params)
    {
        $payChannel = $this->model->carrierPayChannel;
        if (empty($payChannel)) {
            throw new \Exception('运营商支付渠道未设置，请联系运营商', 40001);
        }
        $params['sign'] = $this->rsaSign($params);
        //$params['callBackParam']=urlencode('callBackParam');
        //$params['timeoutExpress']=$this->timeoutExpress();
        return parent::sendData($params);
    }
    public function getamount()
    {
        $temamount=$this->model->amount*100;
        return intval($temamount);
    }
    public function paymentModeCode()
    {
        return urlencode(json_encode(array('paymentModeCode'=>$this->model->bank_no)));
    }

    public function timeoutExpress()
    {
        return time()+3600;
    }
    public function getNowDate()
    {
        return date('YmdHis');
    }
}