<?php

namespace App\Http\Controllers;

use App\Models\tblOrder;
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

    public function withdrawal()
    {
        return response()->json([
            'data' => $this->getOrder('WITHDRAWAL')
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
        $request =tblOrder::with(['actor', 'warehouse'])->where('fkWarehouseIDNo', $warehouseIDNo)
            ->where('transactionType', $transactionType)
            ->paginate(5);

        return $request;
    }
}
