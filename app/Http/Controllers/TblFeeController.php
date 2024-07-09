<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Models\tblFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TblFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => tblFee::paginate(5)
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeeRequest $request)
    {
        $fee = tblFee::create($request->except('warehouseIDNo') + [
            'user_id' => Auth::user()->load('operator')->id,
            'fkWarehouseIDNo' => $request->input("warehouseIDNo")
        ]);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $fee
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblFee  $tblFee
     * @return \Illuminate\Http\Response
     */
    public function show(tblFee $fee)
    {
        return response()->json([
            'data' => $fee
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblFee  $tblFee
     * @return \Illuminate\Http\Response
     */
    public function update(FeeRequest $request, tblFee $fee)
    {
        $fee = $fee->update($request->all() + [
            'lastUpdatedByName' => 'Cole Baidoo'
        ]);

        if (!$fee)
        {
            return response()->json([
                'message' => 'Update failed',
            ], 204);  
        }

        return response()->json([
            'message' => 'Updated successfully',
        ], 200);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tblFee  $tblFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblFee $fee)
    {
        $fee->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ], 204);
    }
}
