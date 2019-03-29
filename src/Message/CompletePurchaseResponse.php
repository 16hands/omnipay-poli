<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Poli Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * CompletePurchaseResponse constructor.
     *
     * @param RequestInterface $request
     * @param array $data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        if (! isset($data['TransactionRefNo'])) {
            throw new InvalidResponseException;
        }

        parent::__construct($request, $data);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return ! $this->getCode() && $this->data['TransactionStatusCode'] === "Completed";
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        if ($this->data['TransactionRefNo']) {
            return $this->data['TransactionRefNo'];
        }
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        if ($this->data['ErrorCode']) {
            return $this->data['ErrorCode'];
        }
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        if ($this->data['ErrorMessage']) {
            return $this->data['ErrorMessage'];
        }
    }
}
