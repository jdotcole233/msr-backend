<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorRequest extends FormRequest
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
            'operatorName' => 'required|string|max:255',
            'contactPhone' => 'required',
            'ageGroup' => 'required',
            'gender' => 'required',
            'is_owner' => 'required',
        ];
    }
}
