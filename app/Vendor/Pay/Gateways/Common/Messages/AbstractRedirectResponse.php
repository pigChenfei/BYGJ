<?php
/**
 * Abstract Response
 */
namespace App\Vendor\Pay\Gateways\Common\Messages;

use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Curl\Curl;
use App\Vendor\Pay\Gateways\Guofubao\Messages\CreateRequest;

/**
 * Abstract Response
 *
 * This abstract class implements ResponseInterface and defines a basic
 * set of functions that all Omnipay Requests are intended to include.
 *
 * Objects of this class or a subclass are usually created in the Request
 * object (subclass of AbstractRequest) as the return parameters from the
 * send() function.
 *
 * Example -- validating and sending a request:
 *
 * <code>
 * $myResponse = $myRequest->send();
 * // now do something with the $myResponse object, test for success, etc.
 * </code>
 *
 * @see ResponseInterface
 */
abstract class AbstractRedirectResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    abstract public function getRedirectUrl();

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    abstract public function getRedirectMethod();

    abstract public function response($response);

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return array
     */
    public function getRedirectData()
    {
        return $this->getData();
    }

    /**
     * Automatically perform any required redirect
     *
     * This method is meant to be a helper for simple scenarios. If you want to customize the
     * redirection page, just call the getRedirectUrl() and getRedirectData() methods directly.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function redirect()
    {
        $this->getRedirectResponse()->send();
        exit();
    }

    /**
     *
     * @return HttpRedirectResponse
     */
    public function getRedirectResponse()
    {
        if (! $this instanceof RedirectResponseInterface || ! $this->isRedirect()) {
            throw new \RuntimeException('This response does not support redirection.');
        }
        
        if (RedirectMethod::GET === $this->getRedirectMethod()) {
            return HttpRedirectResponse::create($this->getRedirectUrl());
        } elseif (RedirectMethod::POST === $this->getRedirectMethod()) {
            \Log::info([
                '请求数据' => $this->getRedirectData()
            ]);
            if ($this->request->formsubmit) {
                $hiddenFields = '';
                foreach ($this->getRedirectData() as $key => $value) {
                    $hiddenFields .= sprintf('<input type="hidden" name="%1$s" value="%2$s" />',
                        htmlentities($key, ENT_QUOTES, 'UTF-8', false), htmlentities($value, ENT_QUOTES, 'UTF-8', false)) .
                         "\n";
                }
                
                $output = '<!DOCTYPE html>
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>Redirecting...</title>
                    </head>
                    <body onload="document.forms[0].submit();">
                    <form action="%1$s" method="post">
                    <p>Redirecting to payment page...</p>
                    <p>
                    %2$s
                    <input type="submit" value="Continue" />
                    </p>
                    </form>
                    </body>
                    </html>';
                $output = sprintf($output, htmlentities($this->getRedirectUrl(), ENT_QUOTES, 'UTF-8', false),
                    $hiddenFields);
                // dd($output);
                // exit();
                return $output;
            } else {
                $curl = new Curl();
                $curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
                $curl->post($this->getRedirectUrl(), $this->getRedirectData());
                if ($curl->error) {
                    throw new \RuntimeException('Curl error "' . $curl->errorMessage . '".');
                } else {
                    $response = $curl->response;
                    return $this->response($response);
                }
            }
        }
        
        throw new \RuntimeException('Invalid redirect method "' . $this->getRedirectMethod() . '".');
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }
}
