<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\WarehouseRequest;
use App\Models\tblOperator;
use App\Models\tblWarehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login (LoginRequest $request)
    {
        $credentials = \request(['phonenumber', 'password']);

        if (!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized, check your credentails and try again'
            ], 401);
        }

        $user = $request->user()->load(['operator', 'operator.warehouse']);
        $token = $user->createToken('Personal Access Token');
        $token = $token->plainTextToken;

        return response()->json([
            'name' => $user->operator->operatorName,
            'warehouse' => $user->operator->warehouse->id,
            'warehouseName' => $user->operator->warehouse->registeredName,
            'warehouseIDNo' => $user->operator->warehouse->warehouseIDNo,
            'operatorID' => base64_encode($user->operator->id),
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
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
            'GPSLat' => $request->input('GPSLat'), 
            'GPSLong' => $request->input('GPSLong')
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
}
