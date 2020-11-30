<?php

use App\Http\Controllers\API\v1\AgentController;
use App\Http\Controllers\API\v1\AgentLoginController;
use App\Http\Controllers\API\v1\BetTransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Auth::routes();
Route::group([
    'prefix' => '/v1/agent'
], function () {
    Route::post('login', [AgentLoginController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/users', [AgentController::class, 'index']);
        Route::post('/transaction', [BetTransactionController::class, 'store']);
        Route::get('/transaction', [BetTransactionController::class, 'validateTransaction']);
        Route::get('/info', [AgentController::class, 'index']);
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/agents', [\App\Http\Controllers\AgentController::class, 'index']);
//    Route::post('/transaction', [BetTransactionController::class, 'store']);
//    Route::get('/transaction', [BetTransactionController::class, 'validateTransaction']);
//    Route::get('/info', [AgentController::class, 'index']);
});




