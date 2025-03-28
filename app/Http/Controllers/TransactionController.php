<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Models\Product;

class TransactionController extends Controller
{
    public function list()
    {
        if (Transaction::count() == 0) {
            return response()->json(['message' => 'Nenhuma transação registrada.'], 404);
        }
        return Transaction::all();
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transação não encontrada.'], 404);
        }

        // Carregar os produtos com a customização do método getProductsAttribute
        $transaction->load('products');

        return response()->json($transaction);
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'card' => 'required|string',
                'cvv' => 'required|string|size:3',
                'products' => 'required|array',
                'products.*.id' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $user = $request->user();
        $products = $request->products;
        $total = 0;
        $transactionProducts = [];
        foreach ($products as $productInput) {
            $product = Product::find($productInput['id']);
            if ($product) {
                if ($product->amount < $productInput['quantity']) {
                    return response()->json(['message' => 'Quantidade de produtos indisponível.'], 400);
                }
                $quantity = $productInput['quantity'];
                $price = $product->price;
                $total += $price * $quantity;
                $transactionProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }
        }
        $total = round($total, 2);
        $paymentService = new PaymentService();
        $paymentReturn = $paymentService->processPayment($total, $user->name, $user->email, $request->card, $request->cvv);

        if ($paymentReturn['status'] == 'error') {
            return response()->json(['message' => 'Erro ao processar pagamento.'], 500);
        }

        $transaction = Transaction::create([
            'external_id' => $paymentReturn['id'],
            'user_id' => $user->id,
            'gateway_id' => $paymentReturn['gateway_id'],
            'total' => $total,
            'card_last_numbers' => substr($request->card, -4),
            'status' => 'paid',
        ]);
        foreach ($transactionProducts as $transactionProduct) {
            $transaction->products()->attach($transactionProduct['product_id'], [
                'quantity' => $transactionProduct['quantity'],
                'price' => $transactionProduct['price'],
            ]);
            $product = Product::find($transactionProduct['product_id']);
            $product->amount -= $transactionProduct['quantity'];
            $product->save();
        }
        return $transaction;
    }

    public function refund($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transação nao encontrada.'], 404);
        }
        $payment = new PaymentService();
        $paymentReturn = $payment->refundPayment($transaction->external_id, $transaction->gateway_id);
        if ($paymentReturn['status'] == 'error') {
            return response()->json(['message' => 'Erro ao processar pagamento.'], 500);
        }
        $transaction->status = 'refunded';
        $transaction->save();
        return $transaction;
    }
}
