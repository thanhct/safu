<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\HomeController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/report/submit', [HomeController::class, 'submitAddress']);

Route::post('/getLook', [HomeController::class, 'lookupAddress']);

Route::post('/changeStatus', [HomeController::class, 'changeStatus']);