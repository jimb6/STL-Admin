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

Route::get('test', function (){
    return User::whereHas('roles', function ($query) {
        $query->where('name', 'super-admin');
    })->get();
});


Route::get('/user', function (Request $request) {
    return response(['user' =>  Auth::guard('api')->user()], 200);
});

Route::prefix('v1/')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::resource('agents', \App\Http\Controllers\API\v1\ApiAgentController::class);
        Route::resource('bets', \App\Http\Controllers\API\v1\ApiBetController::class);
        Route::resource('settings', \App\Http\Controllers\API\v1\ApiAppSettingsController::class);
        Route::resource('games', \App\Http\Controllers\API\v1\ApiGameController::class);
        Route::resource('draw-periods', \App\Http\Controllers\API\v1\ApiDrawPeriodController::class);
        Route::resource('devices', \App\Http\Controllers\API\v1\ApiDeviceController::class);
        Route::resource('bet-transactions', \App\Http\Controllers\API\v1\ApiBetTransactionController::class);
        Route::resource('roles', \App\Http\Controllers\API\v1\ApiRoleController::class);
        Route::resource('permissions', \App\Http\Controllers\API\v1\ApiPermissionController::class);
        Route::resource('users', \App\Http\Controllers\API\v1\ApiUserController::class);
        Route::resource('clusters', \App\Http\Controllers\API\v1\ApiClusterController::class);

        Route::get('bets-range/{date}', [\App\Http\Controllers\API\v1\ApiBetController::class, 'getBetsRange']);

        Route::get('/agents/active/all', [\App\Http\Controllers\API\v1\ApiAgentController::class, 'activeIndex'])
            ->name('agents.active');

        Route::get('count-transactions', function (){
            return response(['transaction' => Bet::whereDate('created_at', Carbon::today())->count()]);
        });

        Route::get('sum-transactions', function (){
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





