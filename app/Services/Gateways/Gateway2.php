<?php

namespace App\Services\Gateways;

use GuzzleHttp\Client;

class Gateway2 implements GatewayInterface
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function createTransaction(int $amount, string $name, string $email, string $cardNumber, string $cvv): array
    {
        $response = $this->client->post('http://localhost:3002/transacoes', [
            'json' => [
                'valor' => $amount,
                'nome' => $name,
                'email' => $email,
                'numeroCartao' => $cardNumber,
                'cvv' => $cvv,
            ],
            'headers' => [
                'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
                'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f',
            ],
        ]);
        $responseArray = json_decode($response->getBody()->getContents(), true);
        $responseArray['status'] = $response->getStatusCode() === 201 ? 'success' : 'error';
        return $responseArray;
    }

    public function refundTransaction(string $transactionId): array
    {
        $response = $this->client->post("http://localhost:3002/transacoes/reembolso", [
            'json' => [
                'id' => $transactionId
            ],
            'headers' => [
                'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
                'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f',
            ],
        ]);
        $responseArray = json_decode($response->getBody()->getContents(), true);
        $responseArray['status'] = $response->getStatusCode() === 201 ? 'success' : 'error';
        return $responseArray;
    }
}
