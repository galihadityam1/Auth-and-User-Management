<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum') -> get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'jwt','prefix' => 'users'], function() {
    Route::get('/getAllUser', [UserController::class, 'getAllUser']);
    Route::post('/createUser', [UserController::class, 'createUser']);
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
});
