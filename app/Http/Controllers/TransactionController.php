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
            return response()->json(['message' => 'Transação nao encontrada.'], 404);
        }
        return $transaction;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer',
                'amount' => 'required|integer',
                'card' => 'required|string',
                'cvv' => 'required|string|size:3',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['message' => 'Produto nao encontrado.'], 404);
        }
        $user = $request->user();
        $total = $product->total($request->amount);
        $payment = new PaymentService();
        $paymentReturn = $payment->processPayment($total, $user->name, $user->email, $request->card, $request->cvv);
        if ($paymentReturn['status'] == 'error') {
            return response()->json(['message' => 'Erro ao processar pagamento.'], 500);
        }

        $transaction = Transaction::create([
            'external_id' => $paymentReturn['id'],
            'user_id' => $user->id,
            'product_id' => $product->id,
            'gateway_id' => $paymentReturn['gateway_id'],
            'price' => $product->price,
            'quantity' => $request->amount,
            'total' => $total,
            'card_last_numbers' => substr($request->card, -4),
            'status' => 'paid',
        ]);
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
