<?php


use App\Http\Controllers\API\v1\AgentController;
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

Route::middleware('auth')->get('/user', function (Request $request) {
    return response(Auth::user());
});
Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth:web')
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

        Route::get('/games/{any}', function (){
            return view('');
        })->name('game.bets');
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
