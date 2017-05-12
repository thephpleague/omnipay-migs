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
                
                'localeCode'         => 'en',

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
                
                'transactionNo' => '1112', 
                
                'user' => 'amauser', 
                'password' => 'amapassword'
            )
        );

        $data = $this->request->getData();
        
        $this->assertSame('F67A5B37393F0903E228C27DD3FF5704DAD672137108BE78E0A9219F229733B7', $data['vpc_SecureHash']);
    }

    /**
     * Test testRefund method
     *
     * @return void
     * @depends testSignature
     */
    public function testRefund()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,
                
                'localeCode'         => 'en',

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
        $this->assertSame('E5000: Merchant [123] does not have the required privilege to use the VirtualPaymentClient API.', $response->getMessage());
    }
}
