<?php

namespace App\Http\Controllers;

use App\Models\tblActor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TblActorController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblActor  $tblActor
     * @return \Illuminate\Http\Response
     */
    public function show(tblActor $actor)
    {
        return response()->json([
            'data' => $actor
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblActor  $tblActor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tblActor $actor)
    {
        $actor = $actor->update($request->all() + [
            'lastUpdatedByName' => Auth::user()->load(['operator'])->operator->operatorName,
        ]);

        if (!$actor)
        {
            return response()->json([
                'message' => 'Update failed',
            ], 204);  
        }

        // Dispatch SMS work

        return response()->json([
            'message' => 'Updated successfully',
        ], 200);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tblActor  $tblActor
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblActor $tblActor)
    {
        //
    }
}
