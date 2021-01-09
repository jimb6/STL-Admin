<?php


use App\Models\Bet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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
Route::post('v1/agent/login', [\App\Http\Controllers\Auth\LoginController::class, 'loginAgent'])
    ->name('agent.login');
Route::post('v1/agent/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logoutAgent'])
    ->name('agent.logout');

Route::get('/forgot-password', function (Request $request) {
    $request->validate(['contact_number' => 'required|max:11']);
    $status = Password::sendResetLink(
        $request->only('mobile')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')
    ->name('password.request');

Route::post('/device/subscribe/{cluster_id}', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'subscribe'])
    ->name('device.subscribe')->middleware('signed');

Route::get('v1/devices/validate/{device}', function ($serial) {
    $device = \App\Models\Device::where('serial_number', $serial)->get();
    return $device != null ?
        response(['device' => $device[0]->device_code], 200) :
        response(['device' => 'UNREGISTERED'], 204);
})->name('device.validate');

Route::get('test', function () {
    return User::whereHas('roles', function ($query) {
        $query->where('name', 'super-admin');
    })->get();
});


Route::get('/user', function (Request $request) {
    return response(['user' => Auth::guard('api')->user()], 200);
});

Route::prefix('v1/')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::resource('agents', \App\Http\Controllers\API\v1\ApiAgentController::class);
        Route::resource('bets', \App\Http\Controllers\API\v1\ApiBetController::class);
        Route::resource('settings', \App\Http\Controllers\API\v1\ApiAppSettingsController::class);
        Route::resource('games', \App\Http\Controllers\API\v1\ApiGameController::class);
        Route::resource('draw-periods', \App\Http\Controllers\API\v1\ApiDrawPeriodController::class);
        Route::resource('bet-transactions', \App\Http\Controllers\API\v1\ApiBetTransactionController::class);
        Route::resource('roles', \App\Http\Controllers\API\v1\ApiRoleController::class);
        Route::resource('permissions', \App\Http\Controllers\API\v1\ApiPermissionController::class);
        Route::resource('users', \App\Http\Controllers\API\v1\ApiUserController::class);
        Route::resource('clusters', \App\Http\Controllers\API\v1\ApiClusterController::class);
        Route::resource('commissions', \App\Http\Controllers\API\v1\ApiWinningCombinationController::class);

//      Devices Route
        Route::get('devices-index', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'index']);
        Route::get('devices-create', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'create']);
        Route::post('devices-store', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'store']);
        Route::put('devices-update/{id}', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'update']);
        Route::put('devices-delete/{id}', [\App\Http\Controllers\API\v1\ApiDeviceController::class, 'destroy']);

        Route::get('agent-per-cluster/{cluster}', [\App\Http\Controllers\API\v1\ApiAgentController::class, 'agentPerCluster']);
        Route::get('bets-range/{game}/{date}', [\App\Http\Controllers\API\v1\ApiBetController::class, 'getBetsRange']);

//      Custom Winning Combinations Request
        Route::post('winning-combinations', [\App\Http\Controllers\API\v1\ApiWinningCombinationController::class, 'show']);
        Route::post('winning-combinations-store', [\App\Http\Controllers\API\v1\ApiWinningCombinationController::class, 'store']);
        Route::put('winning-combinations-verify/{id}', [\App\Http\Controllers\API\v1\ApiWinningCombinationController::class, 'verify']);
        Route::put('winning-combinations-update/{id}', [\App\Http\Controllers\API\v1\ApiWinningCombinationController::class, 'update']);

//      Custom Draw Period Request
        Route::get('draw-periods-categorized/{game}', [\App\Http\Controllers\API\v1\ApiDrawPeriodController::class, 'getCategorizedDrawPeriod']);
        Route::put('close-draw-period/{drawPeriod}', [\App\Http\Controllers\API\v1\ApiDrawPeriodController::class, 'closeDrawPeriod']);

//      Custom Bet Transaction Request
        Route::post('bet-transaction-entries', [\App\Http\Controllers\API\v1\ApiBetTransactionController::class, 'showEntriesBasedOnDateRange']);
        Route::get('bet-transaction-entries/{date}', [\App\Http\Controllers\API\v1\ApiBetTransactionController::class, 'getAgentTransactions']);

//        Custom Bets Request
        Route::get('bets/{game}/{draw}', [\App\Http\Controllers\API\v1\ApiBetController::class, 'index']);
        Route::post('bets-reports/general', [\App\Http\Controllers\API\v1\ApiBetTransactionController::class, 'getGeneralBetsReport']);
        Route::post('bets-reports/combination', [\App\Http\Controllers\API\v1\ApiBetController::class, 'getCombinationBetsReport']);
        Route::get('cluster-categorized', [\App\Http\Controllers\API\v1\ApiClusterController::class, 'getClusterWithCommissions']);

//      Custom Game Configuration Route Request
        Route::get('games/config/{abbreviation}', [\App\Http\Controllers\API\v1\ApiGameController::class, 'configIndex']);
        Route::put('games/config/default/{game}', [\App\Http\Controllers\API\v1\ApiGameController::class, 'configUpdate']);
        Route::put('games/config/days/{game}', [\App\Http\Controllers\API\v1\ApiGameController::class, 'configDaysUpdate']);

//      Custom Controlled Game Combination Request
        Route::post('games/control-combination/{game}', [\App\Http\Controllers\API\v1\ApiControlledNumberController::class, 'store']);
        Route::delete('games/control-combination/{combi}', [\App\Http\Controllers\API\v1\ApiControlledNumberController::class, 'destroy']);
        Route::put('games/control-combination/{game}', [\App\Http\Controllers\API\v1\ApiControlledNumberController::class, 'update']);

//      Custom Closed Game Combination Request
        Route::post('close-combination/{game}/{draw_period}', [\App\Http\Controllers\API\v1\ApiCloseNumberController::class, 'store']);
        Route::patch('close-combination/{game}/{draw}', [\App\Http\Controllers\API\v1\ApiCloseNumberController::class, 'destroy']);

//      Custom Game Configuration Mobile Route Request
        Route::get('games/mobile-config/today/', [\App\Http\Controllers\API\v1\ApiGameController::class, 'configMobileIndex']);


//        Custom User Request
        Route::put('deactivate-user/{id}', [\App\Http\Controllers\API\v1\ApiUserController::class, 'deactivateUser']);

        Route::get('/agents/active/all', [\App\Http\Controllers\API\v1\ApiAgentController::class, 'activeIndex'])
            ->name('agents.active');
        Route::get('count-transactions', function () {
            return response(['transaction' => Bet::whereDate('created_at', Carbon::today())->count()]);
        });

        Route::get('sum-transactions', function () {
            return response(['transaction' => Bet::whereDate('created_at', Carbon::today())->sum('amount')]);
        });

        Route::get('draw-periods-games', [\App\Http\Controllers\API\v1\ApiDrawPeriodController::class, 'getDrawPeriodGames']);

//        Route::post('send-custom-message', [\App\Http\Controllers\SMSController::class, 'sendCustomMessage']);
        Route::post('send-default-message', [\App\Http\Controllers\SMSController::class, 'sendMessage']);

        Route::get('users-list/{role}', [\App\Http\Controllers\API\v1\ApiUserController::class, 'baseRoleIndex']);

        Route::get('/agents/active/all', [\App\Http\Controllers\API\v1\ApiAgentController::class, 'activeIndex'])->name('agents.active');

        // Assigning Permissions in every roles
        Route::post('assign-role-permission', function ($request) {
            $role = $request->input('role');
            $role->givePermissionTo(Permission::find($request->input('permission_id')));
            return response([], 200);
        })->name('role.assign.permission');
    });





