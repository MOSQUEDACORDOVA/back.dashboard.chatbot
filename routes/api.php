<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserType;
use App\Http\Controllers\GeneralCRUDController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->get('/check-session', [AuthController::class, 'checkSession']);

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

    Route::post('/create/{alias}', [GeneralCRUDController::class, 'createRecord']);
    Route::delete('/{alias}/{id}', [GeneralCRUDController::class, 'deleteRecord']);
    Route::get('/{alias}/{id?}', [GeneralCRUDController::class, 'getRecord']);

});


