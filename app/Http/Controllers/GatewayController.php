<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;

class GatewayController extends Controller
{
    public function list()
    {
        return Gateway::all();
    }

    public function show($id)
    {
        $gateway = Gateway::find($id);
        if (!$gateway) {
            return response()->json(['message' => 'Gateway não encontrado.'], 404);
        }
        return $gateway;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'class_name' => 'required',
                'active' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $priority = Gateway::max('priority') + 1;
        $gateway = Gateway::create([
            'name' => $request->name,
            'class_name' => $request->class_name,
            'active' => $request->active,
            'priority' => $priority
        ]);

        return $gateway;
    }

    public function update(Request $request, $id)
    {
        $gateway = Gateway::find($id);
        if (!$gateway) {
            return response()->json(['message' => 'Gateway nao encontrado.'], 404);
        }
        try {
            $request->validate([
                'name' => 'required',
                'class_name' => 'required',
                'active' => 'required',
                'priority' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $gateway->update([
            'name' => $request->name,
            'class_name' => $request->class_name,
            'active' => $request->active,
        ]);
        $gateway->setPriority($request->priority);
        return $gateway;
    }

    public function delete($id)
    {
        $gateway = Gateway::find($id);
        $gateway->delete();
        return $gateway;
    }
}
