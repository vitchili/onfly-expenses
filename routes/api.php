<?php

use App\Http\Controllers\api\ExpenseController;
use App\Http\Controllers\api\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Login routes
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);
Route::get('/user', function (Request $request) {
    return $request->user() ? $request->user() : 'Nenhum usuário logado';
});

//Expenses routes
Route::middleware('auth:sanctum')->prefix('/expense')->group(function () {
    Route::get('/{id?}', [ExpenseController::class, 'show']);
    Route::post('/', [ExpenseController::class, 'store']);
    Route::put('/{id}', [ExpenseController::class, 'update']);
    Route::delete('/{id?}', [ExpenseController::class, 'destroy']);
});
