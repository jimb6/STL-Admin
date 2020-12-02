<?php

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


use App\Http\Controllers\HomeController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\DrawResultController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CloseNumberController;
use App\Http\Controllers\CollectionRecordController;
use App\Http\Controllers\CollectionStatusController;

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

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes(['register' => false]);


Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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

        Route::get('/games/{any}', [\App\Http\Controllers\BetGameController::class, 'index'])->name('game.bets');
    });


//Auth::routes(['register' => false]);
//
//
//Route::get('/home', function () {
//    return redirect()->route('home');
//});
//
//Route::get('/', function () {
//    return redirect()->route('home');
//});
//
//Route::get('/testing-page', function(){
//    return view('test');
//})->name('test');

//Temporary.....

//Route::get('/', 'ChatsController@index')->name('home');
//Route::get('messages', 'ChatsController@fetchMessages');
//Route::post('messages', 'ChatsController@sendMessage');

//End of temporary ....

//Route::middleware('auth')->group(function () {
//    Route::prefix('admin')->group(function () {
//
//
//        Route::get('user/profile', function () {
//            return view('admin/profile');
//        });
//
//        Route::get('agents', function () {
//            return view('agent.agents');
//        })->name('view.agents');
//
//        Route::get('booths', function () {
//            return view('booths');
//        });
//
//        Route::get('winners', function () {
//            return view('winners');
//        });
//
//        Route::get('/add-new-result', function () {
//            return view('add-new-result');
//        });
//
//        Route::get('/add-new-booth', function () {
//            return view('add-new-booth');
//        });
//
//        Route::get('/add-new-agent', function () {
//            return view('add-new-agent');
//        });
//
//        Route::get('/settings/mobile/global', function () {
//            return view('settings.mobile.global');
//        });
//
//        Route::get('/settings/mobile/theme', function () {
//            return view('settings.mobile.theme');
//        });
//
//        Route::get('/bets/{any}', [\App\Http\Controllers\BetTransactionController::class, 'show'])->name('game.bets');
//    });
//});
