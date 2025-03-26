<?php

namespace App\Services\Gateways;

use GuzzleHttp\Client;

class Gateway1 implements GatewayInterface
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    private function authenticate(): string
    {
        $response = $this->client->post('http://localhost:3001/login', [
            'json' => [
                'email' => 'dev@betalent.tech',
                'token' => 'FEC9BB078BF338F464F96B48089EB498',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['token'];
    }

    public function createTransaction(int $amount, string $name, string $email, string $cardNumber, string $cvv): array
    {
        $token = $this->authenticate();
        $response = $this->client->post('http://localhost:3001/transactions', [
            'json' => [
                'amount' => $amount,
                'name' => $name,
                'email' => $email,
                'cardNumber' => $cardNumber,
                'cvv' => $cvv,
            ],
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);
        $responseArray = json_decode($response->getBody()->getContents(), true);
        $responseArray['status'] = $response->getStatusCode() === 201 ? 'success' : 'error';
        return $responseArray;
    }

    public function refundTransaction(string $transactionId): array
    {
        $token = $this->authenticate();
        $response = $this->client->post("http://localhost:3001/transactions/{$transactionId}/charge_back", [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);
        $responseArray = json_decode($response->getBody()->getContents(), true);
        $responseArray['status'] = $response->getStatusCode() === 201 ? 'success' : 'error';
        return $responseArray;
    }
}
