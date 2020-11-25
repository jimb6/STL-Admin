<?php

use App\Http\Controllers\API\v1\AgentController;
use App\Http\Controllers\API\v1\AgentLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::group([
    'prefix' => '/v1/agent'
], function () {
    Route::post('login', [AgentLoginController::class, 'login']);
    Route::group([
        'middleware' => 'auth:agent'
    ], function() {
        Route::get('/check-account', [AgentController::class, 'index']);
//        Route::get('logout', 'API\v1\AccountController@logout');
//        Route::get('info/', 'API\v1\AccountController@user');
//        Route::get('class/{sy}/{term}', 'API\v1\StudentController@myClassSchedule');
//        Route::get('academic-records/{sy}/{term}', 'API\v1\StudentController@academicRecords');
//        Route::get('grades/{sy}/{term}', 'API\v1\StudentController@myGrades');
//        Route::get('assessment/{sy}/{term}', 'API\v1\StudentController@myAssessment');
//        Route::get('ledger', 'API\v1\StudentController@myLedger');
//        Route::get('test', 'API\v1\StudentController@test');
    });
});

