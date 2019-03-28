<?php

namespace Omnipay\Poli\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Poli Purchase Request
 *
 * @link http://www.polipaymentdeveloper.com/doku.php?id=initiate
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate';

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
            'authenticationCode',
            'transactionId',
            'currency',
            'amount',
            'returnUrl',
            'cancelUrl'
        );

        return [
            'Amount' => $this->getAmount(),
            'CurrencyCode' => $this->getCurrency(),

            'MerchantReference' => $this->getCombinedMerchantRef(),
            'MerchantReferenceFormat' => 1,
            'MerchantData' => $this->getTransactionId(),
            // 'MerchantDateTime' => date('Y-m-d\TH:i:s'),
            'MerchantHomePageURL' => $this->getCancelUrl(),

            'SuccessURL' => $this->getReturnUrl(),
            'FailureURL' => $this->getReturnUrl(),
            'CancellationURL' => $this->getCancelUrl(),
            'NotificationURL' => $this->getNotifyUrl(),

            'Timeout' => 900, // 15 minutes
            // 'UserIPAddress' => $this->getClientIp(),
        ];
    }

    /**
     * Generate reference data
     * @link http://www.polipaymentdeveloper.com/doku.php?id=nzreconciliation
     */
    public function getCombinedMerchantRef()
    {
        $card = $this->getCard();
        $id = $this->cleanField($this->getTransactionId());

        if ($card && $card->getName()) {
            $data = [$this->cleanField($card->getName()), "", $id];
            return implode("|", $data);
        }

        return $id;
    }

    /**
     * Data in reference field must not contain illegal characters
     *
     * @param string $field
     * @return bool|string
     */
    protected function cleanField(string $field)
    {
        return substr($field, 0, 12);
    }

    public function send()
    {
        return $this->sendData($this->getData());
    }

    public function sendData($data)
    {
        $merchantCode = $this->getMerchantCode();
        $authenticationCode = $this->getAuthenticationCode();
        $auth = base64_encode($merchantCode.":".$authenticationCode);

        unset($data['MerchantCode'], $data['AuthenticationCode']);

        $httpResponse = $this->httpClient->request('POST',
            $this->endpoint,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.$auth,
            ],
            json_encode($data)
        );

        return $this->response = new PurchaseResponse($this, $httpResponse->getBody());
    }
}
