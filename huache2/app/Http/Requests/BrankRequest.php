<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrankRequest extends FormRequest
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
            'bank_code' => 'required|max:100',
            //'bank_name' => 'required|chinese|min:4|max:30',
            'province' => 'required|min:1',
            'city' => 'required|min:1',
            'bank_address' => 'required|chinese|min:4',
            //'sc_bank_img' => 'required|image',
            //'bank_img'    => 'required|image'
        ];
    }
}
