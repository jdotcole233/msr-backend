<?php

namespace App\Http\Controllers;

use App\Models\tblGIN;
use App\Models\tblInventory;
use App\Models\tblOrder;
use App\Utility\MsrUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TblGINController extends Controller
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
    public function store(Request $request)
    {

        info(json_encode($request->all()));
        $order = tblOrder::find($request->input('requestID'));
        $order->update([
            'isComplete' => MsrUtility::$COMPLETED
        ]);


        $withdrawalOrder = tblGIN::create([
            'user_id' => $request->user()->id,
            'fkWarehouseIDNo' => $order->fkWarehouseIDNo,
            'fkActorID' => $order->fkActorID,
            'fktblWHCommoditiesID' => $request->input('commodityId'),
            'dateIssued' => $request->input('dateIssued'),
            'noBagsIssued' => $request->input('noBagsIssued'),
            'weightPerBag' => $request->input('weightPerBag'),
            'pricePerBag' => $request->input('unit_price'),
            'lastUpdatedByName' => $request->user()->load(['operator'])->operator->operatorName,
            'ginidno' => now()->year. '-'.Str::upper(Str::random(5)), 
            'fkOrderId' => $request->input('requestID')
        ]);

        $inventoryInstance = tblInventory::where('fktblWHCommoditiesID', $order->fkWarehouseIDNo)->first();

        if ($inventoryInstance) {
            $lastQuantity = $inventoryInstance->totalReceived;
            $lastQuantity = $lastQuantity - floatval($request->input('noBagsIssued'));
            $inventoryInstance->update(['totalIssued' => $lastQuantity]);
        }
        
        return response()->json([
            'message' => 'Created successfully',
            'data' => $withdrawalOrder
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblGIN  $tblGIN
     * @return \Illuminate\Http\Response
     */
    public function show(tblGIN $gin)
    {
        return response()->json([
            'data' => $gin
        ], 200);
    }

    public function goodIssued(Request $request)
    {
        $gin = tblGIN::where('ginidno', $request->input('gid'));

        return response()->json([
            'data' => $gin
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tblGIN  $tblGIN
     * @return \Illuminate\Http\Response
     */
    public function edit(tblGIN $tblGIN)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblGIN  $tblGIN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tblGIN $tblGIN)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tblGIN  $tblGIN
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblGIN $tblGIN)
    {
        //
    }
}
