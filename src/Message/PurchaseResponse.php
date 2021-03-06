<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Poli Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Is the result a success?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['Success']) ? $this->data['Success'] : false;
    }

    /**
     * Do we need to redirect?
     *
     * @return bool
     */
    public function isRedirect()
    {
        return isset($this->data['NavigateURL']);
    }

    /**
     * Transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        // The Navigate URL contains a TransactionToken which is an encrypted form of the TransactionRefNo.
        // This is the token that must be used to retrieve the transaction later on.
        if ($this->isRedirect()) {
            $query = parse_url($this->getRedirectUrl(), PHP_URL_QUERY);
            parse_str($query, $params);

            if (isset($params['Token'])) {
                return $params['Token'];
            }
        }

        return $this->getTransactionId();
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['TransactionRefNo'];
    }

    /**
     * Error message, e.g.: '' = no error
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->data['ErrorMessage'];
    }

    /**
     * Error code, e.g.: 0 = no error
     *
     * @return int
     */
    public function getCode()
    {
        return $this->data['ErrorCode'];
    }

    /**
     * Redirection URL
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['NavigateURL'];
        }
    }

    /**
     * Redirection method
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Redirection data
     *
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }
}
