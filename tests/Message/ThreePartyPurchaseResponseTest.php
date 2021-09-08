<?php

namespace Omnipay\Migs\Message;

use Omnipay\Tests\TestCase;

class ThreePartyPurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $data = array('test' => '123');

        $response = new ThreePartyPurchaseResponse($this->getMockRequest(), $data, 'https://example.com/');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame($data, $response->getData());

        $this->assertSame('https://example.com/', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame($data, $response->getRedirectData());
    }
}
