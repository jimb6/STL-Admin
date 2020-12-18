<?php


use App\Http\Controllers\AgentController;
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
Route::post('v1/agent/login', [\App\Http\Controllers\Auth\LoginController::class, 'loginAgent'])->name('agent.login');

Route::get('v1/devices/{device}', function ($serial){
    $device = \App\Models\Device::where('serial_number', $serial)->get();
    return response($device, 200);
})->name('device.validate');


Route::apiResources([
    'agents' => AgentController::class,
    'users' => \App\Http\Controllers\UserController::class,
    'booths' => \App\Http\Controllers\API\v1\BoothController::class,
    'clusters' => \App\Http\Controllers\ClusterController::class,
    'addresses' => \App\Http\Controllers\AddressController::class,
    'bets' => \App\Http\Controllers\BetTransactionController::class,
    'roles' => \App\Http\Controllers\API\v1\RoleController::class,
    'permissions' => \App\Http\Controllers\API\v1\PermissionController::class,
    'devices' => \App\Http\Controllers\DeviceController::class,
    'bet-transactions' => \App\Http\Controllers\BetTransactionController::class,
    'draw-periods' => \App\Http\Controllers\DrawPeriodController::class,
    'games' => \App\Http\Controllers\GameController::class,
]);

Route::middleware(['auth:api'])->group(function () {

    Route::resource('agents', AgentController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('booths', \App\Http\Controllers\API\v1\BoothController::class);
    Route::resource('clusters', \App\Http\Controllers\ClusterController::class);
    Route::resource('addresses', \App\Http\Controllers\AddressController::class);
    Route::resource('bets', \App\Http\Controllers\API\v1\BetController::class);
    Route::resource('roles', \App\Http\Controllers\API\v1\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\API\v1\PermissionController::class);
    Route::resource('devices', \App\Http\Controllers\DeviceController::class);
    Route::resource('bet-transactions', \App\Http\Controllers\BetTransactionController::class);
    Route::resource('draw-periods', \App\Http\Controllers\DrawPeriodController::class);
    Route::resource('games', \App\Http\Controllers\GameController::class);

    // Assigning Permissions in every roles
    Route::post('assign-role-permission', function ($request) {
        $role = $request->input('role');
        $role->givePermissionTo(Permission::find($request->input('permission_id')));
        return response([], 200);
    })->name('role.assign.permission');

});





