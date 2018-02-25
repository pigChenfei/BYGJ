<?php
/**
 * Request Interface
 */

namespace App\Vendor\Pay\Gateways\Common\Messages;

/**
 * Request Interface
 *
 * This interface class defines the standard functions that any Omnipay request
 * interface needs to be able to provide.  It is an extension of MessageInterface.
 *
 * @see MessageInterface
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Initialize request with parameters
     * @param array $parameters The parameters to send
     */
    public function setParameters(array $parameters = array());

    /**
     * Get the response to this request (if the request has been sent)
     *
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function handle();

    /**
     * Send the request with specified data
     *
     * @param  mixed             $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data);
}
