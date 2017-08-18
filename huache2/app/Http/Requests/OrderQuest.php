<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HgOrderAttr;

class OrderQuest extends FormRequest
{

    public $allowed_fields = [
        'or_contact','xzj','non_xzj_list'
    ];

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
            //
        ];
    }


    public function performUpdate(HgOrderAttr $attr)
    {
        $data['or_contact'] = $this->input('or_contact');
        if ($this->has('xzj_list')){
            $data['non_xzj_list'] = implode(',',$this->input('xzj_list'));
        }
        $attr->update($data);
        return $attr;
    }
}
