<?php


use App\Http\Controllers\API\v1\AgentController;
use App\Http\Controllers\API\v1\AgentLoginController;
use App\Http\Controllers\API\v1\BaseController;
use App\Http\Controllers\API\v1\BoothController;
use App\Http\Controllers\API\v1\CloseNumberController;
use App\Http\Controllers\API\v1\CollectionRecordController;
use App\Http\Controllers\API\v1\CollectionStatusController;
use App\Http\Controllers\API\v1\DrawResultController;
use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\PermissionController;
use App\Http\Controllers\API\v1\RoleController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\BetCollectionController;
use App\Http\Controllers\BetController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/{any}', function () {
//    return redirect()->route('home');
//})->where('any', '.*');

Route::get('/', function () {
    return redirect()->route('admin.home');
});

//Route::post('/login', [AgentLoginController::class, 'login']);

Route::middleware('auth:web')->group(function (){
    Route::get('/agents/count', [AgentController::class, 'activeAgents'])->name('agents.active.count');
    Route::get('/booths/count', [BoothController::class, 'getActiveBooths'])->name('booths.active.count');
    Route::get('/collections/daily-sum', [BetCollectionController::class, 'todayBaseCollection'])->name('bet.collection.daily.sum');
});


Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);

        Route::resource('agents', AgentController::class);
        Route::resource('booths', BoothController::class);
        Route::resource('bases', BaseController::class);
        Route::resource('users', UserController::class);
        Route::resource('draw-results', DrawResultController::class);
        Route::resource(
            'collection-records',
            CollectionRecordController::class
        );
        Route::resource(
            'collection-statuses',
            CollectionStatusController::class
        );
        Route::resource('close-numbers', CloseNumberController::class);

        Route::get('/games/{any}', [BetController::class, 'index'])->name('game.bets');
        Route::get('/games/{any}', [BetController::class, 'show'])->name('game.bets');

//        Customize Request

    });
