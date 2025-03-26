<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function list()
    {
        $products = Product::all();
        return $products;
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produto nao encontrado.'], 404);
        }
        return $product;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'amount' => 'required|numeric',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'amount' => $request->amount,
        ]);
        return $product;
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'amount' => 'required|numeric',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produto nao encontrado.'], 404);
        }
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'amount' => $request->amount,
        ]);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produto nao encontrado.'], 404);
        }
        $product->delete();
        return $product;
    }
}
