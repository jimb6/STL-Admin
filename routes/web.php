<?php


use App\Http\Controllers\AgentController;
use App\Http\Controllers\API\v1\BetController;
use App\Http\Controllers\API\v1\BoothController;
use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\BetCollectionController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route::get('/{any}', function () {
//    return redirect()->route('home');
//})->where('any', '.*');

Route::get('/', function () {
    return redirect()->route('admin.home');
});

//Route::post('/login', [AgentLoginController::class, 'login']);

Route::middleware('auth:web')->group(function (){
    Route::get('/booths/count', [BoothController::class, 'getActiveBooths'])->name('booths.active.count');
    Route::get('/collections/daily-sum', [BetCollectionController::class, 'todayBaseCollection'])->name('bet.collection.daily.sum');
});


Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth:web')
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        Route::resource('agents', AgentController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('booths', \App\Http\Controllers\API\v1\BoothController::class);
        Route::resource('clusters', \App\Http\Controllers\ClusterController::class);
        Route::resource('addresses', \App\Http\Controllers\AddressController::class);
        Route::resource('bets', \App\Http\Controllers\API\v1\BetController::class);
        Route::resource('roles', \App\Http\Controllers\API\v1\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\API\v1\PermissionController::class);
        Route::resource('devices', \App\Http\Controllers\DeviceController::class);
        Route::resource('bet-transactions', \App\Http\Controllers\BetTransactionController::class);
        Route::resource('draw-periods', \App\Http\Controllers\DrawPeriodController::class);
        Route::resource('games', \App\Http\Controllers\GameController::class);


        Route::get('/games/{any}', [BetController::class, 'index'])->name('game.bets');
//        Route::get('/games/{any}', [BetController::class, 'show'])->name('game.bets');

        Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile');
//        Route::get('/games/{any}', [BetCollectionController::class, 'index'])->name('game.bets');


//      Settings Controller
        Route::get('settings/app', [\App\Http\Controllers\AppSettingsController::class, 'globalSettings'])->name('settings.global');

    });
