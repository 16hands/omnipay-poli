<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Exception\InvalidResponseException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v2';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://poliapi.uat1.paywithpoli.com/api';

    /**
     * @var string
     */
    protected $liveEndpoint = 'https://poliapi.apac.paywithpoli.com/api';

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

    /**
     * @return \Omnipay\Common\Message\ResponseInterface
     * @throws InvalidResponseException
     */
    public function send()
    {
        return $this->sendData($this->getData());
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        // Remove the MerchantCode and AuthenticationCode, we use this separately in getBasicAuthorizationToken()
        unset($data['MerchantCode'], $data['AuthenticationCode']);

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() === 'GET') {
            $requestUrl = $this->getEndpoint().'?'.http_build_query($data);
            $body = null;
        } else {
            $body = json_encode($data);
            $requestUrl = $this->getEndpoint();
        }

        try {
            $httpResponse = $this->httpClient->request(
                $this->getHttpMethod(),
                $requestUrl,
                [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic '.$this->getBasicAuthorizationToken(),
                    'Content-type' => 'application/json',
                ],
                $body
            );

            // Empty response body should be parsed also as and empty array
            $body = (string)$httpResponse->getBody()->getContents();
            $jsonToArrayResponse = ! empty($body) ? json_decode($body, true) : [];

            return $this->response = $this->createResponse($jsonToArrayResponse, $httpResponse->getStatusCode());

        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: '.$e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Map a response into the appropriate class.
     *
     * @param array $data
     * @param $statusCode
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    abstract protected function createResponse($data, $statusCode);

    /**
     * Get HTTP Method.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Get the endpoint to use.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;

        return $base.'/'.self::API_VERSION;
    }

    /**
     * Get the basic authorization token to use in the header.
     *
     * @return string
     */
    protected function getBasicAuthorizationToken()
    {
        $merchantCode = $this->getMerchantCode();
        $authenticationCode = $this->getAuthenticationCode();

        return base64_encode($merchantCode.":".$authenticationCode);
    }
}
