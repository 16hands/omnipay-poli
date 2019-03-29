<?php

namespace Omnipay\Poli;

use Omnipay\Common\AbstractGateway;

/**
 * Class Gateway
 *
 * @package Omnipay\Poli
 */
class Gateway extends AbstractGateway
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Poli';
    }

    /**
     * @inheritdoc
     */
    public function getDefaultParameters()
    {
        return [
            'merchantCode' => '',
            'authenticationCode' => ''
        ];
    }

    /**
     * @return string
     */
    public function getMerchantCode()
    {
        return $this->getParameter('merchantCode');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantCode($value)
    {
        return $this->setParameter('merchantCode', $value);
    }

    public function getAuthenticationCode()
    {
        return $this->getParameter('authenticationCode');
    }

    public function setAuthenticationCode($value)
    {
        return $this->setParameter('authenticationCode', $value);
    }

    /**
     * Create a purchase request.
     *
     * The InitiateTransaction web service is used to initiate a POLi transaction
     * with details specified by the merchant. POLi will authenticate
     * the merchant's request and validate the data passed.
     *
     * @link https://www.polipayments.com/InitiateTransaction
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Poli\Message\PurchaseRequest', $parameters);
    }

    /**
     *
     * @link https://www.polipayments.com/GetTransaction
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Poli\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @link https://www.polipayments.com/GetTransaction
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|Message\FetchPurchaseRequest
     */
    public function fetchPurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Poli\Message\FetchPurchaseRequest', $parameters);
    }
}
