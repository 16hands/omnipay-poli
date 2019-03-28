<?php

namespace Omnipay\Poli;

use Omnipay\Poli\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * Check that MerchantRef outputs correctly, based on supplied params
     */
    public function testMerchantRef()
    {
        $params = [
            'amount' => '12.00',
            'description' => 'Test Product',
            'currency' => 'NZD',
            'merchantCode' => 'ASDF',
            'authenticationCode' => '1234',
            'transactionId' => 123,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        ];

        $this->request->initialize($params);

        // No card means use standard form
        $this->assertEquals(123, $this->request->getCombinedMerchantRef());
        $data = $this->request->getData();
        $this->assertEquals(123, $data['MerchantReference']);
        $this->assertEquals(1, $data['MerchantReferenceFormat']);

        // Valid card uses combined form
        $params['card'] = $this->getValidCard();
        $this->request->initialize($params);
        $this->assertEquals("Example User||123", $this->request->getCombinedMerchantRef());

        // Missing name means use standard form
        unset($params['card']['firstName']);
        unset($params['card']['lastName']);

        $this->request->initialize($params);

        $this->assertEquals(123, $this->request->getCombinedMerchantRef());
    }

}
