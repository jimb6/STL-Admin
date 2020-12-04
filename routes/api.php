<?php

use App\Http\Controllers\API\v1\AgentController;
use App\Http\Controllers\API\v1\AgentLoginController;
use App\Http\Controllers\API\v1\BaseController;
use App\Http\Controllers\API\v1\BoothController;
use App\Http\Controllers\API\v1\CloseNumberController;
use App\Http\Controllers\API\v1\CollectionRecordController;
use App\Http\Controllers\API\v1\CollectionStatusController;
use App\Http\Controllers\API\v1\DrawResultController;
use App\Http\Controllers\API\v1\PermissionController;
use App\Http\Controllers\API\v1\RoleController;
use App\Http\Controllers\API\v1\UserController;
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
Route::middleware('auth')->get('/user', function (Request $request) {
    return Auth::user();
});
Route::middleware('auth')->get('/agents', [AgentController::class, 'index']);

Route::group([
    'prefix' => '/v1/'
], function () {
    Route::post('login', [AgentLoginController::class, 'login']);

    Route::middleware('sanctum')
        ->group(function () {
//            Route::resource('roles', RoleController::class);
//            Route::resource('permissions', PermissionController::class);
            Route::resource('agents', AgentController::class);
//            Route::resource('booths', BoothController::class);
//            Route::resource('bases', BaseController::class);
//            Route::resource('users', UserController::class);
//            Route::resource('draw-results', DrawResultController::class);
//            Route::resource(
//                'collection-records',
//                CollectionRecordController::class
//            );
//            Route::resource(
//                'collection-statuses',
//                CollectionStatusController::class
//            );
//            Route::resource('close-numbers', CloseNumberController::class);
//
//            Route::get('/games/{any}', function (){
//                return view('');
//            })->name('game.bets');
//            Route::get('/agents/active', [AgentController::class, 'activeAgents']);

            Route::get('agents/sample', function (Request $request) {
                return new \Illuminate\Http\JsonResponse(['Hello World', 200]);
            });
        });

});





