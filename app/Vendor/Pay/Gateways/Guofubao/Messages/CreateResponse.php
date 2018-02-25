<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/1
 * Time: 17:18
 */
namespace App\Vendor\Pay\Gateways\Guofubao\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRedirectResponse;
use App\Vendor\Pay\Gateways\Common\Messages\RedirectMethod;

class CreateResponse extends AbstractRedirectResponse
{

    public function getRedirectUrl()
    {
        if (! empty($this->request->model->carrierPayChannel->bindedThirdPartGateway->merchant_bind_domain)) {
            $url = $this->request->model->carrierPayChannel->bindedThirdPartGateway->merchant_bind_domain;
            if (starts_with('http', $url)) {
                return $url;
            }
            return 'http://' . $url;
        }
        return $this->getRequest()->getURL();
    }

    public function getRedirectMethod()
    {
        return RedirectMethod::POST;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Vendor\Pay\Gateways\Common\Messages\AbstractRedirectResponse::response()
     */
    public function response($data)
    {
        $reqData = $this->getRedirectData();
        if ($data instanceof \stdClass) {
            if ($data->success == 1) {
                return [
                    'amount' => $data->txnAmt,
                    'success' => 200,
                    'signature' => $data->signature,
                    'qrcode' => isset($data->payLink) ? $data->payLink : '',
                    'prepayUrl' => isset($data->prepayUrl) ? $data->prepayUrl : '',
                    'platform' => $platform,
                    'message' => $data->msg
                ];
            } else {
                return [
                    'success' => 4000,
                    'signature' => isset($data->signature) ? $data->signature : '',
                    'message' => $platform ? '请求' . $platform . '平台出错，请联系管理员' : $data->msg
                ];
            }
        }
        return $data;
    }
}