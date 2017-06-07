<?php

namespace Omnipay\Migs\Message;

/**
 * Migs Complete Purchase Request
 */
class ThreePartyRefundRequest extends AbstractRequest
{
    protected $action = 'refund';
    public function getData()
    {
        $this->validate('amount', 'transactionId', 'user', 'password');
        $data = $this->getBaseData();
        $data['vpc_SecureHash']  = $this->calculateHash($data);
        $data['vpc_TransNo'] = $this->getTransactionNo();
        $data['vpc_User']  = $this->getUser();
        $data['vpc_Password']  = $this->getPassword();
        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcdps';
    }
}
