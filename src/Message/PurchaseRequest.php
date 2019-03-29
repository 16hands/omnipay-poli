<?php

namespace Omnipay\Poli\Message;

/**
 * Poli Purchase Request
 *
 * @link https://www.polipayments.com/InitiateTransaction
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
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
            'MerchantDateTime' => date('Y-m-d\TH:i:s'),
            'MerchantHomePageURL' => $this->getCancelUrl(),

            'SuccessURL' => $this->getReturnUrl(),
            'FailureURL' => $this->getReturnUrl(),
            'CancellationURL' => $this->getCancelUrl(),
            'NotificationURL' => $this->getNotifyUrl(),

            'Timeout' => 900, // 15 minutes
            'UserIPAddress' => $this->getClientIp(),
        ];
    }

    /**
     * Generate reference data
     *
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

    /**
     * Map a response into the appropriate class.
     *
     * @param array $data
     * @param $statusCode
     * @return \Omnipay\Common\Message\ResponseInterface|PurchaseResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return new PurchaseResponse($this, $data);
    }
}
