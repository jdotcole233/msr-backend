<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'registeredName' => 'required|max:200|unique:tbl_warehouses',
            'region' => 'required|string',
            'townCity' => 'required|string|max:200',
            'district' => 'required|string|max:200',
            'businessType' => 'required|string',
            'storageCapacity' => 'required',
            'phonenumber' => 'required|string|max:20|unique:users,phonenumber',
            'password' => 'required|confirmed|min:7|max:255'
        ];
    }
}
