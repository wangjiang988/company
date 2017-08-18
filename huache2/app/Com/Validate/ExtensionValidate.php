<?php

namespace App\Com\Validate;
use Illuminate\Validation\Validator;
//use Illuminate\Foundation\Http\FormRequest;
use App\Services\IdCart;


class ExtensionValidate extends Validator
{
    public function __construct($translator, $data, $rules, $messages)
    {
        parent::__construct($translator, $data, $rules, $messages);
    }
    /**
     * 验证手机号
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateMobile($attribute, $value, $parameters)
    {
        if(is_null($value)){
            return false;
        }
        return is_phone($value);
    }

    /**
     * 验证中文
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateChinese($attribute, $value , $parameters)
    {
        if(is_null($value)){
            return false;
        }
        return isChinese($value);
    }

    /**
     * 验证身份证
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIdCart($attribute, $value , $parameters)
    {
        if(is_null($value)){
            return false;
        }
        return IdCart::isCard($value);
    }
    /**
     * 验证字符长度
     * @param type $attribute
     * @param type $value
     * @param type $parameters
     * @return type
     */
    public function validateLength($attribute, $value , $parameters) {
        return (mb_strlen($value) == $parameters[0]);
    }
}