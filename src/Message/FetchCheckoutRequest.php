<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Message\AbstractRequest;

class FetchCheckoutRequest extends AbstractRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction';

    public function getMerchantCode()
    {
        return $this->getParameter('merchantCode');
    }

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

    public function getData()
    {
        $this->validate(
            'merchantCode',
            'authenticationCode'
        );

        return [];
    }

    public function send()
    {
        return $this->sendData($this->getData());
    }

    public function sendData($data)
    {
        $token = $this->httpRequest->query->get('token');
        $url = $this->endpoint.'?token='.urlencode($token);

        $merchantCode = $this->getMerchantCode();
        $authenticationCode = $this->getAuthenticationCode();
        $auth = base64_encode($merchantCode.":".$authenticationCode);

        $httpResponse = $this->httpClient->request('GET', $url, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$auth,
        ]);

        return $this->response = new FetchCheckoutResponse($this, $httpResponse->getBody());
    }
}
