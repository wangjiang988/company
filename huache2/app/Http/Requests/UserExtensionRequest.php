<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use App\Com\Validate\ExtensionValidate;

class UserExtensionRequest extends FormRequest
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
            'real_name'     => 'required|chinese',
            'id_cart'       => 'required|idCart',
            /*'sc_id_cart_img'=> 'required|image',
            'id_facade_img' => 'required|image',
            'id_behind_img' => 'required|image'
            */
        ];
    }
}
