<?php

namespace Omnipay\Migs\Message;

/**
 * Migs Purchase Request
 */
class ThreePartyPurchaseRequest extends AbstractRequest
{
    protected $action = 'pay';

    public function getData()
    {
        $this->validate('amount', 'returnUrl', 'transactionId');

        $data = $this->getBaseData();
        $data['vpc_SecureHash']  = $this->calculateHash($data);
        $data['vpc_SecureHashType']  = 'SHA256';

        return $data;
    }

    public function sendData($data)
    {
        $redirectUrl = $this->getEndpoint();

        return $this->response = new ThreePartyPurchaseResponse($this, $data, $redirectUrl);
    }

    public function getEndpoint()
    {
        if ($this->getParameter('testMode')) {
            return $this->endpointTEST.'vpcpay';
        } else {
            return $this->endpoint.'vpcpay';
        }
    }
}
