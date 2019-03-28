<?php

namespace Omnipay\Poli\Message;

use Guzzle\Http\EntityBodyInterface;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RedirectResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Poli Checkout Response
 *
 */
class FetchCheckoutResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     *
     * @param RequestInterface $request
     * @param StreamInterface $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, json_decode($data, true));
    }

    /**
     * Is the result a success?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return ! $this->getCode();
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
     * Error message, e.g.: '' = no error
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->data['ErrorMessage'];
    }

    /**
     * Error code, e.g.: 0 or '' = no error
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
