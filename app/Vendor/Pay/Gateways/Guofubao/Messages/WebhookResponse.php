<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/1
 * Time: 21:30
 */

namespace App\Vendor\Pay\Gateways\Guofubao\Messages;


use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookResponse;

class WebhookResponse  extends AbstractWebhookResponse
{
    use GenerateSignature;

    public function isSuccessful()
    {
       //return $this->data\
    }
}