<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('auth/register',[AuthController::class,'create']);
Route::post('auth/login',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'getUsuarios']);
    Route::post('/usuarios', [UsuarioController::class,'createUsuario']);
    Route::get('auth/logout', [AuthController::class, 'logout']);
});

