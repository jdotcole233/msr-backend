<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommodityRequest;
use App\Models\tblCommodity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TblCommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => tblCommodity::latest()->paginate(5),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommodityRequest $request)
    {
        $commodity = tblCommodity::create($request->all() + [
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $commodity
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblCommodity  $tblCommodity
     * @return \Illuminate\Http\Response
     */
    public function show(tblCommodity $commodity)
    {
        info($commodity);
        return response()->json([
            'data' => $commodity
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblCommodity  $tblCommodity
     * @return \Illuminate\Http\Response
     */
    public function update(CommodityRequest $request, tblCommodity $commodity)
    {
        $commodity = $commodity->update($request->all() + ['lastUpdatedByName' => 'Cole Baidoo']);

        if (!$commodity) {
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
     * @param  \App\Models\tblCommodity  $tblCommodity
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblCommodity $commodity)
    {
        $commodity->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ], 204);
    }
}
