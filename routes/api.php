<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TblActorController;
use App\Http\Controllers\TblCommodityController;
use App\Http\Controllers\TblFeeController;
use App\Http\Controllers\TblOperatorController;
use App\Http\Controllers\TblWarehouseController;
use App\Http\Controllers\TblOrderController;
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

Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


Route::post('v1/warehouse', [AuthController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        // Route::apiResource('warehouse', TblWarehouseController::class);
        Route::get('warehouse/{warehouse}', [TblWarehouseController::class, 'show']);
        Route::put('warehouse/{warehouse}', [TblWarehouseController::class, 'update']);
        Route::apiResource('commodities', TblCommodityController::class);
        Route::apiResource('fees', TblFeeController::class);
        Route::apiResource('operator', TblOperatorController::class);

        Route::apiResource('actor', TblActorController::class);

        Route::get('storage', [TblOrderController::class, 'storage']);
        Route::get('withdrawal', [TblOrderController::class, 'withdrawal']);
        Route::get('off-take', [TblOrderController::class, 'offtake']);

        Route::put('resetpassword/{user}', [TblOperatorController::class, 'resetOperatorPassword']);
        Route::put('setpassword/{operator}', [TblOperatorController::class, 'setOperatorPassword']);
    });
});