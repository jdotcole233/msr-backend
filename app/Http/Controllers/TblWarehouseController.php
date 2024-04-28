<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\tblOperator;
use App\Models\tblWarehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TblWarehouseController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        $warehouse = tblWarehouse::create([
            'registeredName' => $request->input('registeredName'),
            'region' => $request->input('region'),
            'townCity' => $request->input('townCity'),
            'district' => $request->input('district'),
            'businessType' => $request->input('businessType'),
            'storageCapacity' => $request->input('storageCapacity'),
            'warehouseIDNo' => Str::upper(Str::random(6)),
        ]);

        $operator = tblOperator::create([
            'fkWarehouseIDNo' => $warehouse->warehouseIDNo,
            'contactPhone' => $request->input('phonenumber'),
            'isOwner' => true
        ]);

        $user = User::create([
            'operator_id' => $operator->id,
            'phonenumber' => $request->input('phonenumber'),
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json([
            'message' => 'Created successfully',
            'data' => [
                'warehouse' => $warehouse,
                'operator' => $operator
            ]
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblWarehouse  $tblWarehouse
     * @return \Illuminate\Http\Response
     */
    public function show(tblWarehouse $warehouse)
    {
        return response()->json([
            'data' => $warehouse
        ], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblWarehouse  $tblWarehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tblWarehouse $warehouse)
    {
        $warehouse = $warehouse->update($request->all());

        if (!$warehouse)
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
     * @param  \App\Models\tblWarehouse  $tblWarehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblWarehouse $tblWarehouse)
    {
        //
    }
}
