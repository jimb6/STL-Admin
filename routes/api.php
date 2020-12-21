<?php


use App\Models\User;
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
    $device = \App\Models\Device::where('serial_number', $serial)->count() > 0;
    return $device ? response(['message' => 'REGISTERED'], 200) : response(['message' => 'UNREGISTERED'], 204);
})->name('device.validate');


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

        Route::get('/agents/active/all', [\App\Http\Controllers\API\v1\ApiAgentController::class, 'activeIndex'])->name('agents.active');

        // Assigning Permissions in every roles
        Route::post('assign-role-permission', function ($request) {
            $role = $request->input('role');
            $role->givePermissionTo(Permission::find($request->input('permission_id')));
            return response([], 200);
        })->name('role.assign.permission');
    });





