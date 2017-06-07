<?php

namespace Omnipay\Migs\Message;

use Omnipay\Tests\TestCase;

class ThreePartyRefundRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new ThreePartyRefundRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSignature()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',

                'transactionNo' => '1112',

                'user' => 'amauser',
                'password' => 'amapassword'
            )
        );

        $data = $this->request->getData();

        $this->assertSame('80E8AD6C582431F9C8A55C9645EE2F05BA70D178EB0A85E7394331DC09B61875', $data['vpc_SecureHash']);
    }

    /**
     * @depends testSignature
     */
    public function testRefund()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
                
                'transactionNo' => '1112',

                'user' => 'amauser',
                'password' => 'amapassword'
            )
        );
        
        $response = $this->request->send();
        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('7', $response->getCode());
        $this->assertSame(
            'E5000: Merchant [123] does not have the required privilege to use the VirtualPaymentClient API.',
            $response->getMessage()
        );
    }
}
