<?php

namespace App\Http\Controllers;

use App\Models\tblOrder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDashboardStats() {
        $orders = tblOrder::get()->mapToGroups(function ($item, int $key) {
            return [$item['transactionType'] => $item['id']];
        })->map(function ($item) {
            return collect($item)->count();
        });

        return response()->json([
            'data' => $orders,
        ], 200);
    }
}
