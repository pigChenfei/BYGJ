<?php

namespace App\Vendor\Pay\Gateways\Common\Messages;

abstract class AbstractBackResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        if ($this->getRequest()->isValid()) {
            $payment = $this->getRequest()->getPayment();
            if ($this->getRequest()->isConfirming()) {
                return '/checkout/confirm/' . $payment->hashedId();
            }

            if (!$payment->isMerchantNotified()) {
                return '/checkout/waiting/' . $payment->hashedId();
            }

            return '/checkout/return/' . $payment->hashedId();
        } else {
            return '/checkout/error';
        }
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function redirect()
    {
        redirect($this->getRedirectUrl());
    }
}