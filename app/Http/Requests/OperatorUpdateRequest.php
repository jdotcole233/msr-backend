<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorUpdateRequest extends FormRequest
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
            'isMale' => 'required',
        ];
    }
}
