<?php


use App\Exports\BetEntriesExport;
use App\Exports\GeneralReports;
use App\Exports\UsersExport;
use App\Http\Controllers\HomeController;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
        // Dashboard
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');


        // Bets
        Route::get('bets/all-games/winning-bets', function (Request $request) {
            return view('bets.winning-bets');
        })->name('bets.winning-bets');

        Route::get('bets/{game}', function (Request $request, $abbreviation) {
            if(!Game::where('abbreviation', $abbreviation)->first())  abort(404);
            return view('games.config', with(['abbreviation' => $abbreviation]));
        })->name('bets.game');


        // Reports
        Route::get('reports/overall-gross', function (Request $request) {
            return view('reports.gross');
        })->name('reports.gross');


        // Clusters
        Route::get('clusters', function (Request $request) {
            return view('clusters.index');
        })->name('clusters.index');


        // Agents
        Route::get('agents', function (Request $request) {
            return view('agents.index');
        })->name('agents.index');


        // Devices
        Route::get('devices', function (Request $request) {
            return view('devices.index');
        })->name('devices.index');


        // Booths
        Route::get('booths', function (Request $request) {
            return view('booths.index');
        })->name('booths.index');


        // Games
        Route::get('games', function (Request $request) {
            return view('games.index');
        })->name('games.index');

        Route::get('games', function (Request $request) {
            return view('games.create');
        })->name('games.create');




        // Draw Periods
        Route::get('draw-periods', function (Request $request) {
            return view('draw_periods.index');
        })->name('draw_periods.index');


        // Users
        Route::get('users/{role}', function (Request $request, $role) {
            return view('users.index', with(['role' => $role]));
        })->name('users.index');

        Route::get('addresses', function (Request $request) {
            return view('addresses.index');
        })->name('addresses.index');


        // Settings
        Route::get('settings/permissions', function (Request $request) {
            return view('settings.permissions.index');
        })->name('settings/permissions.index');

        Route::get('settings/roles', function (Request $request) {
            return view('settings.roles.index');
        })->name('settings/roles.index');

        Route::get('settings/app', [\App\Http\Controllers\API\v1\ApiAppSettingsController::class, 'globalSettings'])->name('settings.global');


        // Account
        Route::get('user/profile', [\App\Http\Controllers\API\v1\ApiUserController::class, 'showProfile'])->name('user.profile');

        Route::get('user/info', function () {
            return Auth::check() ? Auth::user()->toJson() : null;
        })->name('user.info');



        // Unknown ==========================================================================
        Route::get('collections', function (Request $request) {
            return view('collections.index');
        })->name('collections.index');

        Route::get('test', function () {
            return view('test');
        });
//        Route::resource('devices', \App\Http\Controllers\API\v1\ApiDeviceController::class);




    });



Route::prefix('reports')->middleware(['signed', 'auth'])->group(function () {
    Route::get('users/generate', function (Request $request) {
        $filename = Carbon::now()->format('Ymdhms') . '-users.xlsx';
        return Excel::download(new UsersExport, $filename);
    })->name('reports.users.generate');

    Route::get('bet-entries/generate', function (Request $request) {
        $filename = Carbon::now()->format('Ymdhms') . '-bet-entries.xlsx';
        return Excel::download(new BetEntriesExport($request), $filename);
    })->name('reports.bet.entries.generate');

    Route::get('bet-general-reports/generate', function (Request $request) {
        $filename = Carbon::now()->format('Ymdhms') . '-general-reports.xlsx';
        return Excel::download(new GeneralReports($request), $filename);
    })->name('reports.bet.general.generate');

    // Reports by Agent

});
