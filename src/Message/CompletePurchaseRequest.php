<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Poli Complete Purchase Request
 *
 * @link http://www.polipaymentdeveloper.com/doku.php?id=gettransaction
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction';

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
            //this may be a POST nudge request, so look for the token there
            $token = $this->httpRequest->request->get('Token');
        }

        if (! $token) {
            throw new InvalidRequestException('Transaction token is missing');
        }

        return [
            'token' => $token
        ];
    }

    public function send()
    {
        return $this->sendData($this->getData());
    }

    public function sendData($data)
    {
        $query = http_build_query($data);
        $url = $this->endpoint.'?'.$query;

        $merchantCode = $this->getMerchantCode();
        $authenticationCode = $this->getAuthenticationCode();
        $auth = base64_encode($merchantCode.":".$authenticationCode);

        $response = $this->httpClient->request('GET', $url, [
            'Authorization' => 'Basic '.$auth,
        ]);

        return $this->response = new CompletePurchaseResponse($this, $response->getBody());
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }
}
