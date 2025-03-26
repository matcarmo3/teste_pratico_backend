<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\TransactionController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(AuthMiddleware::class);

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/users', [UserController::class, 'list'])->middleware(RoleMiddleware::class . ':manager');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(RoleMiddleware::class . ':manager');
    Route::post('/users', [UserController::class, 'create'])->middleware(RoleMiddleware::class . ':manager');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware(RoleMiddleware::class . ':manager');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->middleware(RoleMiddleware::class . ':manager');

    Route::get('/products', [ProductController::class, 'list'])->middleware(RoleMiddleware::class . ':manager,finance');
    Route::get('/products/{id}', [ProductController::class, 'show'])->middleware(RoleMiddleware::class . ':manager,finance');
    Route::post('/products', [ProductController::class, 'create'])->middleware(RoleMiddleware::class . ':manager,finance');
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware(RoleMiddleware::class . ':manager,finance');
    Route::delete('/products/{id}', [ProductController::class, 'delete'])->middleware(RoleMiddleware::class . ':manager,finance');

    Route::get('/gateways', [GatewayController::class, 'list'])->middleware(RoleMiddleware::class . ':admin');
    Route::get('/gateways/{id}', [GatewayController::class, 'show'])->middleware(RoleMiddleware::class . ':admin');
    Route::post('/gateways', [GatewayController::class, 'create'])->middleware(RoleMiddleware::class . ':admin');
    Route::put('/gateways/{id}', [GatewayController::class, 'update'])->middleware(RoleMiddleware::class . ':admin');
    Route::delete('/gateways/{id}', [GatewayController::class, 'delete'])->middleware(RoleMiddleware::class . ':admin');

    Route::get('/transactions', [TransactionController::class, 'list'])->middleware(RoleMiddleware::class . ':finance');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->middleware(RoleMiddleware::class . ':finance');
    Route::post('/transactions/{id}/refund', [TransactionController::class, 'refund'])->middleware(RoleMiddleware::class . ':finance');

    Route::get('/user/transactions', [UserController::class, 'transactions']);
    Route::post('/transactions', [TransactionController::class, 'create']);
});
