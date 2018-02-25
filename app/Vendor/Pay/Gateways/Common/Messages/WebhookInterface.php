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
interface WebhookInterface extends MessageInterface
{
    /**
     * handle the webhook
     *
     * @return ResponseInterface
     */
    public function handle();
}
