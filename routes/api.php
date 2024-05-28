<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TblActorController;
use App\Http\Controllers\TblCommodityController;
use App\Http\Controllers\TblFeeController;
use App\Http\Controllers\TblGINController;
use App\Http\Controllers\TblGRNController;
use App\Http\Controllers\TblOperatorController;
use App\Http\Controllers\TblWarehouseController;
use App\Http\Controllers\TblOrderController;
use App\Http\Ussd\States\Welcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Sparors\Ussd\Facades\Ussd;

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

Route::post('v1/ussd', function (Request $request) {
    info(json_encode($request->all()));
    info(json_encode($request['MSISDN']));
    $ussd = Ussd::machine()
        ->setFromRequest([
            'user_id' => 'USERID',
            'phone_number' => 'MSISDN',
            'input' => 'USERDATA',
            'network' => 'NETWORK',
            'msg_type' => 'MSGTYPE',
            'session_id' => 'SESSIONID',
        ])
        ->setInitialState(Welcome::class)
        ->setResponse(function(string $message, string $action) use ($request) {
            return [
                'USERID' => 'hartech1',
                'MSISDN' => $request->input('MSISDN'),
                'MSG' => $message,
                'MSGTYPE' => strcmp($action, 'input') == 0,
            ];
        });

    return response()->json($ussd->run());
});


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        // Route::apiResource('warehouse', TblWarehouseController::class);
        Route::get('warehouse/{warehouse}', [TblWarehouseController::class, 'show']);
        Route::put('warehouse/{warehouse}', [TblWarehouseController::class, 'update']);
        Route::apiResource('commodities', TblCommodityController::class);
        Route::apiResource('fees', TblFeeController::class);
        Route::apiResource('operator', TblOperatorController::class);
        Route::apiResource('process-goods-received', TblGRNController::class);

        Route::apiResource('goods-issued-note', TblGINController::class);

        Route::post('good-issued', [TblGINController::class, 'goodIssued']);
        Route::get('goods-to-be-processed', [TblOrderController::class, 'goodsToBeProcessed']);
        Route::get('goods-for-storage', [TblOrderController::class, 'goodsForStorage']);
        Route::get('goods-bought', [TblOrderController::class, 'goodsBought']);
        Route::get('grn-processed', [TblOrderController::class, 'grnProcessed']);
        Route::get('gin-processed', [TblOrderController::class, 'ginProcessed']);
        Route::get('stock-on-hand', [TblOrderController::class, 'stockOnHand']);
    

        // Route::post('process-withdrawal', [TblOrderController::class, 'processWithdrawal']);

        Route::apiResource('actor', TblActorController::class);

        Route::get('storage', [TblOrderController::class, 'storage']);
        Route::get('withdrawal', [TblOrderController::class, 'withdrawal']);
        Route::get('withdrawn', [TblOrderController::class, 'withdrawn']);
        Route::get('off-take', [TblOrderController::class, 'offtake']);

        Route::put('resetpassword/{user}', [TblOperatorController::class, 'resetOperatorPassword']);
        Route::put('setpassword/{operator}', [TblOperatorController::class, 'setOperatorPassword']);

        Route::put('orderStatus/{order}', [TblOrderController::class, 'orderState']);
    });
});