<?php


use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/device/unsubscribe/{device}', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'unsubscribe'])
    ->name('device.unsubscribe');

Route::get('/', function () {
    return redirect()->route('admin.home');
});

Route::get('/user', function () {
    return response(Auth::user(), 200);
});


Auth::routes(['register' => false]);
Route::prefix('admin')
    ->middleware(['auth:web'])
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        Route::get('agents', function (Request $request) {
            return view('agents.index');
        })->name('agents.index');                                   // Agents\

        Route::get('users/{role}', function (Request $request, $role) {
            return view('users.index', with(['role' => $role]));
        })->name('users.index');                                    // Users

        Route::get('addresses', function (Request $request) {
            return view('addresses.index');
        })->name('addresses.index');                                // Address
        Route::get('devices', function (Request $request) {
            return view('devices.index');
        })->name('devices.index');                                  // Devices
        Route::get('draw-periods', function (Request $request) {
            return view('draw_periods.index');
        })->name('draw_periods.index');

        // Draw Periods
        Route::get('games', function (Request $request) {
            return view('games.index');
        })->name('games.index');

        Route::get('games/create', function (Request $request) {
            return view('games.create');
        })->name('games.create');

        Route::get('games/categorize/{abbreviation}', function (Request $request, $abbreviation) {
            return view('games.config', with(['abbreviation' => $abbreviation]));
        })->name('games.abbreviation.config');

        // Games
        Route::get('booths', function (Request $request) {
            return view('booths.index');
        })->name('booths.index');                                    // Booths
        Route::get('permissions', function (Request $request) {
            return view('settings.permissions.index');
        })->name('permissions.index');                               // Permissions
        Route::get('roles', function (Request $request) {
            return view('settings.roles.index');
        })->name('roles.index');

        Route::get('bets/{game}', function (Request $request, $game) {
            return view('bets.index', with(['game' => $game]));
        })->name('bets.index');                                    // Bets

        Route::get('bases', function (Request $request) {
            return view('bases.index');
        })->name('bases.index');
        // Bets
        Route::get('collections', function (Request $request) {
            return view('collections.index');
        })->name('collections.index');

        Route::get('reports/overall-gross', function (Request $request) {
            return view('reports.gross');
        })->name('reports.gross');

        Route::get('reports', function (Request $request) {
            return view('reports.index');
        })->name('reports.index');
//        Route::resource('devices', \App\Http\Controllers\API\v1\ApiDeviceController::class);


        Route::get('user/profile', [\App\Http\Controllers\API\v1\ApiUserController::class, 'showProfile'])->name('user.profile');
        Route::get('settings/app', [\App\Http\Controllers\API\v1\ApiAppSettingsController::class, 'globalSettings'])->name('settings.global');
        Route::get('user/info', function () {
            return Auth::check() ? Auth::user()->toJson() : null;
        })->name('user.info');

        Route::get('test', function (){ return view('test'); });
    });
