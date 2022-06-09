<?php

namespace Appiersign\PeoplePay;

use phpDocumentor\Reflection\Types\This;

class PeoplePay
{
    private $apiKey;
    private $merchantId;
    private $bearerToken;
    private $transactionId;
    private $baseUrl;

    public function __construct($baseUrl, $apiKey, $merchantId, $transactionId = null)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->merchantId = $merchantId;
        $this->transactionId = $transactionId;
    }

    private function httpRequest($method, $endpoint, $bearerToken = '', $data = [])
    {
        $curl = curl_init();
        $url = "{$this->baseUrl}/peoplepay/hub/$endpoint";

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $bearerToken],
        );

        if ($method === 'POST') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        return $curl;
    }

    private function postRequest($endpoint, $token, $data = [])
    {
        return $this->httpRequest('POST', $endpoint, $token, $data);
    }

    private function getRequest($endpoint, $token = '')
    {
        $request = $this->httpRequest('GET', $endpoint, $token);

        if ($error = curl_error($request)) return [
            'success' => false,
            'message' => $error
        ];

        return $this->jsonToArray(curl_exec($request));
    }


    /**
     */
    public function getBearerToken()
    {
        $request = $this->postRequest('token/get', '', [
            'merchantId' => '62976b446449d8d3b9708b94',
            'apikey' => 'f202925d-36e9-49e1-9649-af11e87f13d3'
        ]);

        return $this->processRequest($request);
    }

    private function postWithBearer($endpoint, $data)
    {
        $bearerResponse = $this->getBearerToken();
        if ($bearerResponse['success'] && $token = $bearerResponse['data']) {
            $request = $this->postRequest($endpoint, $token, $data);
            return $this->processRequest($request);
        }
        return $this->internalErrorResponse();
    }

    public function collect($accountNumber, $accountIssuer, $amount, $callbackUrl, $description)
    {
        return $this->postWithBearer('collectmoney', [
            'account_number' => $accountNumber,
            'account_issuer' => $accountIssuer,
            'amount' => $amount,
            'callbackUrl' => $callbackUrl,
            'description' => $description
        ]);
    }

    private function processRequest($request)
    {
        $response = curl_exec($request);
        curl_close($request);

        if ($error = curl_error($request)) return [
            'success' => false,
            'message' => $error
        ];

        return $this->jsonToArray($response);
    }

    public function getAccountName($accountNumber, $accountIssuer)
    {
        return $this->postWithBearer('enquiry', [
            'account_number' => $accountNumber,
            'account_issuer' => $accountIssuer
        ]);
    }

    public function disburse()
    {

    }

    public function checkStatus($transactionId)
    {
        return $this->getRequest('transactions/get/' . $transactionId, $this->getBearerToken()['data']);
    }

    public function responseMessage($code = '01')
    {
        return [
            '01' => 'pending',
            '00' => 'success',
            '02' => 'failed'
        ][$code];
    }

    private function internalErrorResponse()
    {
        return [
            'success' => 'false',
            'message' => 'something went wrong, please try again later'
        ];
    }

    private function jsonToArray($response)
    {
        return json_decode($response, true);
    }
}