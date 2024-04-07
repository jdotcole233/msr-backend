<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorRequest;
use App\Models\tblOperator;
use App\Models\tblWarehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TblOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => tblOperator::paginate(5)
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OperatorRequest $request)
    {
        $grant_access = $request->input('grant_access');
        $random_password = Str::random(9);

        $operator = tblOperator::create($request->except('grant_access', 'gender', 'phonenumber') + [
            'user_id' => 1,
            'contactPhone' => $request->input('phonenumber'),
            'fkWarehouseIDNo' => '3LFSLK',
            'isMale' => strcmp($request->input('gender'), 'Male') == 0
        ]);

        if ($grant_access) {
            $user = User::create([
                'operator_id' => $operator->id,
                'phonenumber' => $request->input('phonenumber'),
                'password' => Hash::make($random_password),
            ]);

            if ($user)
            {
                //Dispatch SMS Job
            }
        }

        return response()->json([
            'message' => 'Created successfully',
            'data' => $operator
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tblOperator  $operator
     * @return \Illuminate\Http\Response
     */
    public function show(tblOperator $operator)
    {
        return response()->json([
            'data' => $operator
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tblOperator  $tblOperator
     * @return \Illuminate\Http\Response
     */
    public function update(OperatorRequest $request, tblOperator $operator)
    {
        $operator = $operator->update($request->all() + [
            'lastUpdatedByName' => 'Cole Baidoo'
        ]);

        if (!$operator)
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
     * @param  \App\Models\tblOperator  $tblOperator
     * @return \Illuminate\Http\Response
     */
    public function destroy(tblOperator $operator)
    {
        $operator->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ], 204);
    }


    public function resetOperatorPassword (User $user)
    {
        $generate_password = Str::random(9);
        $userUpdated = $user->update([
            'password' => Hash::make($generate_password)
        ]);

        if (!$userUpdated)
        {
            return response()->json([
                'message' => 'Reset failed'
            ], 200);
        }


        //Dispatch SMS to user

        return response()->json([
            'message' => 'Reset successfuly'
        ], 200);
    }
}
