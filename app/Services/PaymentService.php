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
        foreach ($this->gateways as $gateway) {
            $gatewayClass = 'App\\Services\\Gateways\\' . $gateway->class_name;
            if (class_exists($gatewayClass)) {
                $gatewayInstance = app($gatewayClass);
                $response = $gatewayInstance->createTransaction($amount, $name, $email, $cardNumber, $cvv);
                if ($response['status'] != 'error') {
                    $response['gateway_id'] = $gateway->id;
                    return $response;
                }
            } else {
                continue;
            }
        }
        return ['status' => 'error', 'message' => 'All gateways failed to process payment.'];
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
