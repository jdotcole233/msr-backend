<?php

namespace App\Http\Controllers;

use App\Http\Requests\tblGRNRequest;
use App\Models\tblGRN;
use App\Models\tblInventory;
use App\Models\tblOrder;
use App\Utility\MsrUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TblGRNController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(tblGRNRequest $request)
    {
        $operator = $request->user()->load('operator')->operator;

        info(json_encode($request->all()));

        $order = tblOrder::where('id', $request->input('requestID'))->update([
            'iscomplete' => MsrUtility::$COMPLETED,
            'lastUpdatedByName' => $operator->operatorName,
        ]);

        $data = tblGRN::create($request->all() + [
            'user_id' => $request->user()->id,
            'fkWarehouseIDNo' => $operator->fkWarehouseIDNo,
            'lastUpdatedByName' => $operator->operatorName,
            'grnidno' => Str::upper(Str::random(10)),
            'fkOrderId' => $request->input('requestID')
        ]);

        $inventoryInstance = tblInventory::where('fktblWHCommoditiesID', $request->input('fktblWHCommoditiesID'))->first();

        if ($inventoryInstance) {
            $lastQuantity = $inventoryInstance->totalReceived;
            $lastQuantity = $lastQuantity + floatval($request->input('noBagsReceived'));
            $inventoryInstance->update(['totalReceived' => $lastQuantity]);
        } else {
            tblInventory::create([
                'fkWarehouseIDNo' => $request->user()->load(['operator'])->operator->fkWarehouseIDNo, 
                'fktblWHCommoditiesID' => $request->input('fktblWHCommoditiesID'),
                'totalReceived' => $request->input('noBagsReceived'), 
                'totalIssued' => 0,
            ]);
        }

        

        return response()->json([
            'data' => $data,
            'message' => 'Goods processed successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblGRN  $tblGRN
     * @return \Illuminate\Http\Response
     */
    public function show(tblGRN $tblGRN)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tblGRN  $tblGRN
     * @return \Illuminate\Http\Response
     */
    public function edit(tblGRN $tblGRN)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblGRN  $tblGRN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tblGRN $process_goods_received)
    {
        $operator = $request->user()->load('operator')->operator;

        info("grn ". json_encode($process_goods_received));

        $data = $process_goods_received->update($request->all() + [
            'lastUpdatedByName' => $operator->operatorName,
        ]);

        $inventoryInstance = tblInventory::where('fktblWHCommoditiesID', $request->input('fktblWHCommoditiesID'))->first();

        

        if ($inventoryInstance) {
            $lastQuantity = $inventoryInstance->totalReceived;
            $lastQuantity = $lastQuantity + floatval($request->input('noBagsReceived'));
            $inventoryInstance->update(['totalReceived' => $lastQuantity]);
        } else {
            tblInventory::create([
                'fkWarehouseIDNo' => $request->user()->load(['operator'])->operator->fkWarehouseIDNo, 
                'fktblWHCommoditiesID' => $request->input('fktblWHCommoditiesID'),
                'totalReceived' => $request->input('noBagsReceived'), 
                'totalIssued' => 0,
            ]);
        }

    
        return response()->json([
            'data' => $data,
            'message' => 'Goods processed successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tblGRN  $tblGRN
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblGRN $tblGRN)
    {
        //
    }
}
