<?php


use App\Http\Controllers\API\v1\AgentController;
use App\Http\Controllers\API\v1\AgentLoginController;
use http\Client\Request;
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
//Auth::routes();
Route::middleware('auth.basic')->group(function () {
   Route::get('/user', function (){
      return Auth::user();
   });
});


Route::group([
    'prefix' => '/v1/agents'
], function () {

});





