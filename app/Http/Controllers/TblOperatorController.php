<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorRequest;
use App\Http\Requests\OperatorUpdateRequest;
use App\Jobs\OperatorOnboardedJob;
use App\Jobs\ResetPasswordJob;
use App\Models\tblOperator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'data' => tblOperator::latest()->where('fkWarehouseIDNo', Auth::user()->load(['operator'])->operator->fkWarehouseIDNo)->paginate(5)
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
        $password = Hash::make($random_password);

        $operator = tblOperator::create([
            'user_id' => Auth::user()->id,
            'contactPhone' => $request->input('contactPhone'),
            'isMale' => strcmp($request->input('gender'), 'Male') == 0,
            'ageGroup' => $request->input('ageGroup'),
            'operatorName' => $request->input('operatorName'),
            'fkWarehouseIDNo' => $request->input('fkWarehouseIDNo'),
            'isOwner' => $request->input('is_owner')
        ]);

        if ($grant_access) {
            $user = User::create([
                'operator_id' => $operator->id,
                'phonenumber' => $request->input('contactPhone'),
                'password' => $password,
            ]);

            if ($user) {
                //Dispatch SMS Job
                $operator = new OperatorOnboardedJob(
                    $request->input('contactPhone'),
                    $request->input('operatorName'),
                    $random_password
                );
                dispatch($operator);
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
    public function update(OperatorUpdateRequest $request, tblOperator $operator)
    {
        $operator = $operator->update($request->all() + [
            'lastUpdatedByName' => Auth::user()->load(['operator'])->operator->operatorName,
        ]);

        if (!$operator) {
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

    public function setOperatorPassword(Request $request, tblOperator $operator)
    {
        $userUpdated = $operator->user->update([
            'password' => Hash::make($request->input("password"))
        ]);

        if (!$userUpdated) {
            return response()->json([
                'message' => 'Setting password failed. Contact admin'
            ], 200);
        }


        //Dispatch SMS to user

        return response()->json([
            'message' => 'New password set successfuly'
        ], 200);
    }


    public function resetOperatorPassword(tblOperator $operator)
    {
        $generate_password = Str::random(9);
        info("Operator user ". json_encode($operator->user));
        $userUpdated = $operator->user->update([
            'password' => Hash::make($generate_password)
        ]);
        info("upadeted". json_encode($userUpdated));

        if (!$userUpdated) {
            return response()->json([
                'message' => 'Reset failed'
            ], 200);
        }

        //Dispatch SMS to user
        dispatch(new ResetPasswordJob($operator, $generate_password));
        return response()->json([
            'message' => 'Reset successfuly. Check operator sms for new password'
        ], 200);
    }
}
