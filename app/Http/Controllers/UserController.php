<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list()
    {
        $users = User::all();
        return $users;
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
        return $user;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4',
                'role' => 'required|in:user,manager,finance,admin',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $authenticatedUser = $request->user();

        if ($authenticatedUser->role !== 'admin' && $request->role === 'admin') {
            return response()->json(['message' => 'Você não tem permissão para criar administradores.'], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);
        return $user;
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:user,manager,finance,admin',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        $authenticatedUser = $request->user();
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
        if ($authenticatedUser->role !== 'admin' && ($request->role === 'admin' || $user->role === 'admin')) {
            return response()->json(['message' => 'Você não tem permissão para editar administradores.'], 403);
        }
        if ($request->has('password') && !empty($request->password)) {
            $password = bcrypt($request->password);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password ?? $user->password,
            'role' => $request->role
        ]);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
        $authenticatedUser = request()->user();
        if ($authenticatedUser->role !== 'admin' && $user->role === 'admin') {
            return response()->json(['message' => 'Você não tem permissão para deletar administradores.'], 403);
        }
        $user->delete();
        return $user;
    }

    public function transactions()
    {
        $user = request()->user();
        if (!$user->transactions()->exists()) {
            return response()->json(['message' => 'Nenhuma transação encontrada.'], 404);
        }
        return $user->transactions()->get();
    }
}
