<?php

namespace Omnipay\Poli\Message;

class FetchPurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchantCode',
            'authenticationCode'
        );

        return [
            'token' => $this->httpRequest->query->get('token'),
        ];
    }

    /**
     * Get HTTP Method.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * Map a response into the appropriate class.
     *
     * @param array $data
     * @param $statusCode
     * @return \Omnipay\Common\Message\ResponseInterface|FetchPurchaseResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return new FetchPurchaseResponse($this, $data);
    }
}
