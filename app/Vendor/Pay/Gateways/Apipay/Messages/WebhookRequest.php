<?php
namespace App\Vendor\Pay\Gateways\Apipay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookRequest;

class WebhookRequest extends AbstractWebhookRequest
{
    use GenerateSignature;

    public $statusCode = 1;

    public $statusKey = 'r1_Code';

    public $orderNumKey = 'r2_TrxId';

    public function handle()
    {
        $input = $this->getRequestData();
        if (empty($this->model)) {
            \Log::info('订单不存在');
            return;
        }
        $params = array(
            'p1_MerId' => $this->model->carrierPayChannel->bindedThirdPartGateway->merchant_number,
            'r0_Cmd' => $input['r0_Cmd'],
            'r1_Code' => $input['r1_Code'],
            'r2_TrxId' => $this->model->pay_order_number,
            'r3_Amt' => $this->model->amount,
            'r4_Cur' => $input['r4_Cur'],
            'r5_Pid' => $input['r5_Pid'],
            'r6_Order' => $input['r6_Order'],
            'r7_Uid' => $input['r7_Uid'],
            'r8_MP' => $input['r8_MP'],
            'r9_BType' => $input['r9_BType']
        );
        $signature = $this->getReqHmacString($params,
            $this->model->carrierPayChannel->bindedThirdPartGateway->private_key);
        //&& array_get($input, 'hmac') === $signature
        if (array_key_exists('hmac', $input)) {
            $response = parent::handle();
            \Log::info('========= ========    apipay 通知处理成功   ========= ========');
            return $response;
        } else {
            \Log::error('========= ========       apipay 通知签名错误     ========= ========');
        }
    }
}