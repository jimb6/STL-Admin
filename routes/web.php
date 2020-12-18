<?php


use App\Http\Controllers\AgentController;
use App\Http\Controllers\API\v1\BetController;
use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
header('Access-Control-Allow-Origin: *');


Route::get('/device/unsubscribe/{device}', [\App\Http\Controllers\DeviceController::class, 'unsubscribe'])
    ->name('device.unsubscribe');


Route::post('/device/subscribe/', [\App\Http\Controllers\DeviceController::class, 'subscribe'])
    ->name('device.subscribe')->middleware('signed');

Route::get('/', function () {
    return redirect()->route('admin.home');
});


Auth::routes(['register' => false]);
Route::prefix('admin')
    ->middleware(['auth:web'])
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        Route::resource('agents', AgentController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('booths', \App\Http\Controllers\API\v1\BoothController::class);
        Route::resource('clusters', \App\Http\Controllers\ClusterController::class);
        Route::resource('addresses', \App\Http\Controllers\AddressController::class);
        Route::resource('bets', \App\Http\Controllers\API\v1\BetController::class)->only([
            'index', 'store', 'show'
        ]);
        Route::resource('roles', \App\Http\Controllers\API\v1\RoleController::class)->only([
            'index', 'store', 'show'
        ]);
        Route::resource('permissions', \App\Http\Controllers\API\v1\PermissionController::class)->only([
            'index', 'store', 'show'
        ]);
        Route::resource('devices', \App\Http\Controllers\DeviceController::class);
        Route::resource('bet-transactions', \App\Http\Controllers\BetTransactionController::class)->only([
            'index', 'store', 'show'
        ]);
        Route::resource('draw-periods', \App\Http\Controllers\DrawPeriodController::class);
        Route::resource('games', \App\Http\Controllers\GameController::class);

        Route::get('/games/{any}', [BetController::class, 'index'])->name('game.bets');
        Route::get('/agents/active/all', [AgentController::class, 'activeIndex'])->name('game.bets');
        Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile');
        Route::get('settings/app', [\App\Http\Controllers\AppSettingsController::class, 'globalSettings'])->name('settings.global');

        Route::get('user/info', function (){
            return Auth::check()? Auth::user()->toJson():null;
        })->name('user.info');
    });
