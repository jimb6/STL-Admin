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



Auth::routes(['register' => false]);


Route::get('/home', function () {
    return redirect()->route('home');
});

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/testing-page', function(){
    return view('test');
})->name('test');

//Temporary.....

//Route::get('/', 'ChatsController@index')->name('home');
//Route::get('messages', 'ChatsController@fetchMessages');
//Route::post('messages', 'ChatsController@sendMessage');

//End of temporary ....

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::get('user/profile', function () {
            return view('admin/profile');
        });

        Route::get('agents', function () {
            return view('agents');
        })->name('view.agents');

        Route::get('booths', function () {
            return view('booths');
        });

        Route::get('winners', function () {
            return view('winners');
        });

        Route::get('/add-new-result', function () {
            return view('add-new-result');
        });

        Route::get('/add-new-booth', function () {
            return view('add-new-booth');
        });

        Route::get('/add-new-agent', function () {
            return view('add-new-agent');
        });
    });
});
