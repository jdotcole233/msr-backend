<?php

use App\Http\Controllers\TblCommodityController;
use App\Http\Controllers\TblFeeController;
use App\Http\Controllers\TblOperatorController;
use App\Http\Controllers\TblWarehouseController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('v1')->group(function () {
    Route::apiResource('warehouse', TblWarehouseController::class);
    Route::apiResource('commodities', TblCommodityController::class);
    Route::apiResource('fees', TblFeeController::class);
    Route::apiResource('operator', TblOperatorController::class);

    Route::put('resetpassword/{user}', [TblOperatorController::class, 'resetOperatorPassword']);
});