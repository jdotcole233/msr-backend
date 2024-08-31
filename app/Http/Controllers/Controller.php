<?php

namespace App\Http\Controllers;

use App\Models\tblOrder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDashboardStats() {
        $warehouse = Auth::user()->operator->fkWarehouseIDNo;
        info("warehouse id for stats ". json_encode($warehouse));
        $orders = tblOrder::where('fkWarehouseIDNo', $warehouse)->get()->mapToGroups(function ($item, int $key) {
            return [$item['transactionType'] => $item['id']];
        })->map(function ($item) {
            return collect($item)->count();
        });

        info("ware house stats". json_encode($orders));

        return response()->json([
            'data' => $orders,
        ], 200);
    }
}
