<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Poli Complete Purchase Request
 *
 * @link http://www.polipaymentdeveloper.com/doku.php?id=gettransaction
 */
class CompletePurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction';

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchantCode',
            'authenticationCode'
        );

        $token = $this->getToken();

        if (! $token) {
            $token = $this->httpRequest->query->get('token');
        }

        if (! $token) {
            // This may be a POST nudge request, so look for the token there
            $token = $this->httpRequest->request->get('Token');
        }

        if (! $token) {
            throw new InvalidRequestException('Transaction token is missing');
        }

        return [
            'token' => $token
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
     * Get the token to use in the request.
     *
     * @return string|mixed
     */
    public function getToken()
    {
        return $this->getParameter('token');
    }

    /**
     * Map a response into the appropriate class.
     *
     * @param array $data
     * @param $statusCode
     * @return \Omnipay\Common\Message\ResponseInterface|CompletePurchaseResponse
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    protected function createResponse($data, $statusCode)
    {
        return new CompletePurchaseResponse($this, $data);
    }
}
