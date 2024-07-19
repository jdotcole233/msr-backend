<?php

namespace App\Http\Controllers;

use App\Jobs\OrderNotificationJob;
use App\Models\tblCommodity;
use App\Models\tblGIN;
use App\Models\tblGRN;
use App\Models\tblInventory;
use App\Models\tblOrder;
use App\Models\tblWarehouse;
use App\Utility\MsrUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TblOrderController extends Controller
{
    public function storage()
    {

        return response()->json([
            'data' => $this->getOrder('STORAGE')
        ], 200);
    }


    public function buyOrder()
    {
        return response()->json([
            'data' => $this->getOrder('BUY ORDER')
        ], 200);
    }


    public function withdrawn()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ["WITHDRAWAL", "WITHDRAW"])
            // ->WhereNull('status')
            ->Where('status', MsrUtility::$STATUS_ACCEPTED)
            // ->where('isComplete', MsrUtility::$COMPLETED)
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function buyOrderToBeProcessed()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ["BUY ORDER"])
            // ->WhereNull('status')
            ->Where('status', MsrUtility::$STATUS_ACCEPTED)
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$UNCOMPLETED)
                    ->orWhereNull('isComplete');
            })
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }


    public function withdrawlOrderToProcess()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ["WITHDRAWAL", "WITHDRAW"])
            // ->WhereNull('status')
            ->Where('status', MsrUtility::$STATUS_ACCEPTED)
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$UNCOMPLETED)
                    ->orWhereNull('isComplete');
            })
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
                $query
                    ->whereIn('isComplete', [MsrUtility::$UNCOMPLETED, MsrUtility::$COMPLETEDASSESSMENTFAILED])
                    ->where('status', MsrUtility::$STATUS_ACCEPTED);
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
        $request = tblOrder::with(['actor', 'warehouse', 'grn'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ['STORAGE', 'OFFTAKE'])
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$COMPLETED);
                // ->orWhereNull('isComplete');
            })
            ->whereHas('grn', function ($query) {
                $query->whereNull('dateReceived');
            })
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }


    public function goodsForStorage()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse', 'grn'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', 'STORAGE')
            ->where('isComplete', MsrUtility::$COMPLETED)
            ->where('status', MsrUtility::$STATUS_ACCEPTED)
            ->whereHas('grn', function ($query) {
                $query->whereNotNull('dateReceived');
            })
            ->paginate(5);

        info("data " . json_encode($request));

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function goodsBought()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        $request = tblOrder::with(['actor', 'warehouse', 'grn'])
            ->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', 'OFFTAKE')
            ->where('isComplete', MsrUtility::$COMPLETED)
            ->where('status', MsrUtility::$STATUS_ACCEPTED)
            ->whereHas('grn', function ($query) {
                $query->whereNotNull('dateReceived');
            })
            ->paginate(5);

        return response()->json([
            'data' => $request
        ], 200);
    }

    public function grnProcessed()
    {
        $grn = tblGRN::with(['commodity', 'actor', 'order'])
            ->paginate(5);

        return response()->json([
            'data' => $grn
        ], 200);
    }

    public function ginProcessed()
    {
        $gin = tblGIN::with(['commodity', 'actor', 'order'])
            ->paginate(5);

        return response()->json([
            'data' => $gin
        ], 200);
    }
    public function stockOnHand()
    {
        $inventory = tblInventory::with(['commodity'])
            ->paginate(5);
        return response()->json([
            'data' => $inventory
        ], 200);
    }

    public function withdrawal()
    {
        $warehouseIDNo = Auth::user()->load(['operator'])->operator->fkWarehouseIDNo;
        info("warehouse " . json_encode($warehouseIDNo));
        $request = tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->whereIn('transactionType', ["WITHDRAWAL", "WITHDRAW"])
            ->where(function ($query) {
                $query->where('isComplete', MsrUtility::$UNCOMPLETED)
                    ->orWhereNull('isComplete');
            })
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 0);
            })->paginate(5);
        info("request " . json_encode($request));

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
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 0);
            })
            ->paginate(5);

        return $request;
    }

    public function orderState(Request $request, tblOrder $order)
    {
        $orderState = $order->update([
            'status' => strcmp($request->input('status'), 'ACCEPTED') === 0
                ? MsrUtility::$STATUS_ACCEPTED
                : MsrUtility::$STATUS_DECLINED,
            'lastUpdatedByName' => Auth::user()->load(['operator'])->operator->operatorName,
            'isComplete' => in_array($order->transactionType, ['STORAGE', 'OFFTAKE']) ? MsrUtility::$UNCOMPLETED : null
        ]);

        if (!$orderState) {
            return response()->json([
                'message' => 'Approving order failed.'
            ], 200);
        }

        $order = tblOrder::find($order->id);
        dispatch(new OrderNotificationJob($order));

        return response()->json([
            'data' => $request->input('status')
        ], 200);
    }


    public function qualityAssessmentUpdate(Request $request, tblOrder $order)
    {
        $user = $request->user()->load('operator');
        $data = json_decode($request->input("assessment"));

        $commodityName = json_decode($order->orderDetails)->commodityName;
        $nameParts = explode(" ", $commodityName);
        info("parts ". json_encode($nameParts));
        $name = $nameParts[0];

        $commodity = tblCommodity::where('commodityName', $name)
        ->where('fkWarehouseIDNo', $order->fkWarehouseIDNo);

        if (count($nameParts) > 1) {
            $size = $nameParts[1];
            $commodity = $commodity->where('packingSize', $size)->first();
        } else 
        {
            $commodity = $commodity->first();
        }

        info("commodity ". json_encode($commodity));


        $data = tblGRN::create([
            'user_id' => $user->id,
            'fkWarehouseIDNo' => $user->operator->fkWarehouseIDNo,
            'lastUpdatedByName' => $user->operator->operatorName,
            'grnidno' => now()->year . "-" . Str::upper(Str::random(10)),
            'assessment' => $request->input("assessment"),
            'fkOrderId' => $order->id,
            'fkActorID' => $order->fkActorID,
            'fktblWHCommoditiesID' => $commodity->id
        ]);

        $completed = MsrUtility::$COMPLETED;
        if (strcmp(strtoupper(trim($request->state)), 'REJECT') == 0) {
            info("Passed test ");
            $completed = MsrUtility::$COMPLETEDASSESSMENTFAILED;
        }

        $order->update([
            'isComplete' => $completed,
            'lastUpdatedByName' => $user->operator->operatorName
        ]);

        if (!$data) {
            return response()->json([
                'message' => 'Quality Assessment failed...'
            ], 200);
        }

        // dispatch(new OrderNotificationJob($order));

        return response()->json([
            'data' => [
                'message' => "Assessment completed successfully...",
                'isComplete' => strcmp($data->status, "accept") === 0 ? MsrUtility::$COMPLETED : MsrUtility::$COMPLETEDASSESSMENTFAILED
            ]
        ], 200);
    }
}
