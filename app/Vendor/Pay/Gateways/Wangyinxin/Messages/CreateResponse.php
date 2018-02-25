<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2018/1/2
 * Time: 21:24
 */
namespace App\Vendor\Pay\Gateways\Wangyinxin\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRedirectResponse;
use App\Vendor\Pay\Gateways\Common\Messages\RedirectMethod;

class CreateResponse extends AbstractRedirectResponse
{

    public function getRedirectUrl()
    {
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
    public function response($response)
    {
        // TODO Auto-generated method stub
    }
}