<?php

namespace App\Services\Gateways;

interface GatewayInterface
{
    /**
     * Cria uma transação com o gateway de pagamento.
     *
     * @param int $amount O valor da transação em centavos.
     * @param string $name O nome do comprador.
     * @param string $email O email do comprador.
     * @param string $cardNumber O número do cartão de crédito.
     * @param string $cvv O código CVV do cartão.
     * @return array|object Retorna a resposta da transação (array ou objeto, dependendo da implementação).
     */
    public function createTransaction(int $amount, string $name, string $email, string $cardNumber, string $cvv): array;

    /**
     * Realiza o reembolso de uma transação.
     *
     * @param int|string $transactionId O ID da transação a ser reembolsada.
     * @return array|object Retorna a resposta do reembolso.
     */
    public function refundTransaction(string $transactionId): array;
}
