<?php

namespace App\Http\Controllers;

use App\Jobs\OrderNotificationJob;
use App\Models\tblGIN;
use App\Models\tblGRN;
use App\Models\tblInventory;
use App\Models\tblOrder;
use App\Models\tblWarehouse;
use App\Utility\MsrUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TblOrderController extends Controller
{
    public function storage()
    {

        return response()->json([
            'data' => $this->getOrder('STORAGE')
        ], 200);
    }


    public function buyOrder() {
        return response()->json([
            'data' => $this->getOrder('BUY ORDER')
        ], 200);
    }


    public function withdrawn()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', "WITHDRAWAL")
            ->WhereNull('status')
            ->orWhere('status', MsrUtility::$STATUS_ACCEPTED)
            ->where('isComplete', MsrUtility::$COMPLETED)
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }


    public function qualityAssessment()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ['STORAGE', 'OFFTAKE'])
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$UNCOMPLETED);
                    // ->orWhereNull('isComplete');
            })
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }


    public function goodsToBeProcessed()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ['STORAGE', 'OFFTAKE'])
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$COMPLETED);
                    // ->orWhereNull('isComplete');
            })
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }


    public function goodsForStorage()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', 'STORAGE')
            ->where('isComplete', MsrUtility::$COMPLETED)
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function goodsBought()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', 'OFFTAKE')
            ->where('isComplete', MsrUtility::$COMPLETED)
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function grnProcessed() {
        $grn = tblGRN::with(['commodity', 'actor'])
        ->paginate(5);

        return response()->json([
            'data' => $grn
        ], 200);
    }

    public function ginProcessed() {
        $gin = tblGIN::with(['commodity', 'actor'])
        ->paginate(5);

        return response()->json([
            'data' => $gin
        ], 200);
    }
    public function stockOnHand() {
        $inventory = tblInventory::with(['commodity'])
        ->paginate(5);
        return response()->json([
            'data' => $inventory
        ], 200);
    }

    public function withdrawal()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', "WITHDRAWAL")
            ->where('isComplete', MsrUtility::$UNCOMPLETED)
            ->WhereIn('status', [null, MsrUtility::$STATUS_ACCEPTED])
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function offtake()
    {
        return response()->json([
            'data' => $this->getOrder('OFFTAKE')
        ], 200);
    }


    private function getOrder(string $transactionType)
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', $transactionType)
            ->whereNull('status')
            ->paginate(5);

        return $request;
    }

    public function orderState(Request $request, tblOrder $order)
    {
        info("order " . json_encode($order));
        $orderState = $order->update([
            'status' => strcmp($request->input('status'), 'ACCEPTED') === 0
                ? MsrUtility::$STATUS_ACCEPTED
                : MsrUtility::$STATUS_DECLINED,
            'lastUpdatedByName' => Auth::user()->load(['operator'])->operator->operatorName,
            'isComplete' =>  in_array($order->transactionType, ['STORAGE', 'OFFTAKE'])  ? MsrUtility::$UNCOMPLETED : null
        ]);

        if (!$orderState) {
            return response()->json([
                'message' => 'Approving order failed.'
            ], 200);
        }

        // dispatch(new OrderNotificationJob($order));

        return response()->json([
            'data' => $request->input('status')
        ], 200);
    }

}
