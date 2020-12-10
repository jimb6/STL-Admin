<?php


use App\Http\Controllers\API\v1\BaseController;
use App\Http\Controllers\API\v1\BetController;
use App\Http\Controllers\API\v1\BoothController;
use App\Http\Controllers\API\v1\CloseNumberController;
use App\Http\Controllers\API\v1\CollectionRecordController;
use App\Http\Controllers\API\v1\CollectionStatusController;
use App\Http\Controllers\API\v1\DrawResultController;
use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\PermissionController;
use App\Http\Controllers\API\v1\RoleController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\BetCollectionController;
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
    Route::get('/booths/count', [BoothController::class, 'getActiveBooths'])->name('booths.active.count');
    Route::get('/collections/daily-sum', [BetCollectionController::class, 'todayBaseCollection'])->name('bet.collection.daily.sum');
});


Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth:web')
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        Route::get('roles', function (){ return view('settings.roles.index');})->name('roles.index');
        Route::get('permissions', function (){ return view('settings.permissions.index');})->name('permissions.index');
        Route::get('booths', function (){ return view('booths.index');})->name('booths.index');
        Route::get('booths/create', function (){ return view('booths.index');})->name('booths.create');
        Route::get('bases', function (){ return view('bases.index');})->name('bases.index');
        Route::get('users', function (){ return view('users.index');})->name('users.index');
        Route::get('agents', function (){ return view('users.index');})->name('agents.index');
        Route::get('agents/create', function (){ return view('users.index');})->name('agents.create');
        Route::get('roles', function () { return view('settings.roles.index');})->name('roles.index');
        Route::get('roles/create', function (){return view('settings.roles.create');})->name('roles.create');
        Route::get('permissions', function () { return view('settings.permissions.index');})->name('permissions.index');
        Route::get('permissions/create', function (){return view('settings.permissions.create');})->name('permissions.create');


        Route::get('/games/{any}', [BetController::class, 'index'])->name('game.bets');
//        Route::get('/games/{any}', [BetController::class, 'show'])->name('game.bets');

        Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile');
//        Route::get('/games/{any}', [BetCollectionController::class, 'index'])->name('game.bets');


//      Settings Controller
        Route::get('settings/app', [\App\Http\Controllers\AppSettingsController::class, 'globalSettings'])->name('settings.global');

    });
