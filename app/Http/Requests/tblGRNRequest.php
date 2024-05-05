<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class tblGRNRequest extends FormRequest
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
            'fkActorID' => 'required',
            'fktblWHCommoditiesID' => 'required',
            'dateReceived' => 'required',
            'noBagsReceived' => 'required',
            'weightPerBag' => 'required',
            'transactionType' => 'required',
            'consigmentValue' => 'nullable',
            'maxStorageDuration' => 'nullable',
            'isCleaned' => 'required',
            'isDried' => 'required',
            'requestID' => 'required',
        ];
    }
}
