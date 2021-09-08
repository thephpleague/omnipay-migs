<?php

namespace Omnipay\Migs\Message;

use Omnipay\Tests\TestCase;

class ThreePartyPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new ThreePartyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSignature()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,
                'returnUrl'          => 'https://www.example.com/return',
                'localeCode'         => 'en',

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
            )
        );

        $data = $this->request->getData();

        $this->assertSame('F4EAA0FB5C06BDB32ECD6DADCDF7832A119D9E5114CBEFDC228370ECC3AE304F', $data['vpc_SecureHash']);
    }

    public function testPurchase()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,
                'returnUrl'          => 'https://www.example.com/return',
                'localeCode'         => 'en',

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
            )
        );

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Migs\Message\ThreePartyPurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());

        $this->assertSame('https://migs.mastercard.com.au/vpcpay', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertArrayHasKey('vpc_SecureHash', $response->getData());
    }
}
