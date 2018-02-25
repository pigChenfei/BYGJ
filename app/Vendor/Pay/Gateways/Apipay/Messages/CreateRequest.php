<?php
namespace App\Vendor\Pay\Gateways\Apipay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRequest;
use App\Vendor\Pay\Gateways\Common\Messages\RequestMethod;
use App\Models\CarrierPayChannel;

class CreateRequest extends AbstractRequest
{
    use GenerateSignature;

    public $method = RequestMethod::FORM_POST;

    public $is_redirect = true;

    public $parameters = [
        'p0_Cmd',
        'p1_MerId',
        'p2_Order', // 订单id
        'p3_Amt',
        'p4_Cur', // 币种
        'p5_Pid', // 商品名称
        'p6_Pcat', // 商品种类
        'p7_Pdesc',
        // 'frontUrl' => 'attribute:returnURL', // 前端返回地址
        'p8_Url', // 后端通知地址
        'p9_SAF',
        'pa_MP', // 商户扩展信息
        'pd_FrpId',
        'pd_FrpId'
    ];

    public function goodDesc()
    {
        $good_name = $this->model->carrierPayChannel->bindedThirdPartGateway->good_name;
        return $good_name . "是采用国内领先的技术，获得大家一致好评";
    }

    public function sendData($params)
    {
        $payChannel = $this->model->carrierPayChannel;
        if (empty($payChannel)) {
            throw new \Exception('运营商支付渠道未设置，请联系运营商', 40001);
        }
        $params['hmac'] = $this->getReqHmacString($params, $payChannel->bindedThirdPartGateway->private_key);
        \Log::info([
            '订单号' . $params['p2_Order'] . 'signature' => $params['hmac']
        ]);
        return parent::sendData($params);
    }
}