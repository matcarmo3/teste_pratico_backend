<?php

namespace App\Services;

use App\Models\Gateway;
use App\Models\Transaction;

class PaymentService
{
    private $gateways = [];
    public function __construct()
    {
        $this->gateways = Gateway::where('active', true)->orderBy('priority', 'desc')->get();
    }

    public function processPayment(int $amount, string $name, string $email, string $cardNumber, string $cvv): array
    {
        $successfulResponse = null;
        foreach ($this->gateways as $gateway) {
            $gatewayClass = 'App\\Services\\Gateways\\' . $gateway->class_name;
            if (class_exists($gatewayClass)) {
                $gatewayInstance = app($gatewayClass);
                try {
                    $response = $gatewayInstance->createTransaction($amount, $name, $email, $cardNumber, $cvv);
                    if ($response['status'] != 'error') {
                        $response['gateway_id'] = $gateway->id;
                        $successfulResponse = $response;
                        break;
                    } else {
                    }
                } catch (\Exception $e) {
                    // Aqui pode jogar pra um log os gateways que não funcionarem para ir analisando
                }
            } else {
                continue;
            }
        }
        if ($successfulResponse) {
            return $successfulResponse;
        } else {
            return ['status' => 'error'];
        }
    }

    public function refundPayment(string $transactionId, int $gatewayId): array
    {
        $gateway = Gateway::find($gatewayId);
        $gatewayClass = 'App\\Services\\Gateways\\' . $gateway->class_name;
        if (class_exists($gatewayClass)) {
            $gatewayInstance = app($gatewayClass);
            $response = $gatewayInstance->refundTransaction($transactionId);
            if ($response['status'] != 'error') {
                return $response;
            }
        }
        return ['status' => 'error', 'message' => 'Gateway failed to refund payment.'];
    }
}
