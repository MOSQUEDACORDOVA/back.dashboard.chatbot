<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeliveryController;
use App\Http\Middleware\CheckUserType;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->get('/check-session', [AuthController::class, 'checkSession']);

    //Crear registro
    Route::post('/delivery', [DeliveryController::class, 'store']);
    //Esitar registro
    Route::put('/delivery/{id}', [DeliveryController::class, 'update']);
    //Eliminar registro
    Route::delete('/delivery/{id}', [DeliveryController::class, 'destroy']);

    // Crear y editar usuario
    Route::middleware(CheckUserType::class)->group(function () {
        //Crear usuario
        Route::post('/register', [AuthController::class, 'register']);
        //Esitar Usuario
        Route::put('/users/{id}', [UserController::class, 'update']);
        //Crear usuario
        Route::post('/register', [AuthController::class, 'register']);
        //Ver usuarios
        Route::get('/users', [UserController::class, 'index']);
        //Eliminar usuario
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    //Ver delivery
    Route::get('/delivery', [DeliveryController::class, 'index']);

});



