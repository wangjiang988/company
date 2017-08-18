<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | 此项 following language lines contain 此项 default error messages used by
    | 此项 validator class. Some of 此项se rules have multiple versions such
    | as 此项 size rules. Feel free to tweak each of 此项se messages here.
    |
    */

    'accepted'             => '此项 :为必填项.',
    'active_url'           => '此项 :不是一个正确的URL.',
    'after'                => '此项 :最后一个不是一个正确的日期格式.',
    'after_or_equal'       => '此项 :必须是一个正确的日期格式.',
    'alpha'                => '此项 :attribute may only contain letters.',
    'alpha_dash'           => '此项 :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => '此项 :attribute may only contain letters and numbers.',
    'array'                => '此项 :attribute must be an array.',
    'before'               => '此项 :attribute must be a date before :date.',
    'before_or_equal'      => '此项 :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => '此项 :attribute must be between :min and :max.',
        'file'    => '此项 :attribute must be between :min and :max kilobytes.',
        'string'  => '此项 :attribute must be between :min and :max characters.',
        'array'   => '此项 :attribute must have between :min and :max items.',
    ],
    'boolean'              => '此项 :attribute field must be true or false.',
    'confirmed'            => '此项 :attribute confirmation does not match.',
    'date'                 => '此项 :attribute is not a valid date.',
    'date_format'          => '此项 :attribute does not match the format :format.',
    'different'            => '此项 :attribute and :other must be different.',
    'digits'               => '此项 :attribute must be :digits digits.',
    'digits_between'       => '此项 :attribute must be between :min and :max digits.',
    'dimensions'           => '此项 :attribute has invalid image dimensions.',
    'distinct'             => '此项 :attribute field has a duplicate value.',
    'email'                => '此项 :attribute must be a valid email address.',
    'exists'               => '此项 selected :attribute is invalid.',
    'file'                 => '此项 :attribute must be a file.',
    'filled'               => '此项 :attribute field is required.',
    'image'                => '此项 :attribute must be an image.',
    'in'                   => '此项 selected :attribute is invalid.',
    'in_array'             => '此项 :attribute field does not exist in :other.',
    'integer'              => '此项 :attribute must be an integer.',
    'ip'                   => '此项 :attribute must be a valid IP address.',
    'json'                 => '此项 :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => '此项 :attribute may not be greater than :max.',
        'file'    => '此项 :attribute may not be greater than :max kilobytes.',
        'string'  => '此项 :attribute may not be greater than :max characters.',
        'array'   => '此项 :attribute may not have more than :max items.',
    ],
    'mimes'                => '此项 :attribute must be a file of type: :values.',
    'mimetypes'            => '此项 :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => '此项 :attribute must be at least :min.',
        'file'    => '此项 :attribute must be at least :min kilobytes.',
        'string'  => '此项 :attribute must be at least :min characters.',
        'array'   => '此项 :attribute must have at least :min items.',
    ],
    'not_in'               => '此项选择 :attribute 是无效的.',
    'numeric'              => '此项 :attribute must be a number.',
    'present'              => '此项 :attribute field must be present.',
    'regex'                => '此项 :attribute format is invalid.',
    'required'             => '此项 :attribute field is required.',
    'required_if'          => '此项 :attribute field is required when :other is :value.',
    'required_unless'      => '此项 :attribute field is required unless :other is in :values.',
    'required_with'        => '此项 :attribute field is required when :values is present.',
    'required_with_all'    => '此项 :attribute field is required when :values is present.',
    'required_without'     => '此项 :attribute field is required when :values is not present.',
    'required_without_all' => '此项 :attribute field is required when none of :values are present.',
    'same'                 => '此项 :attribute and :other must match.',
    'size'                 => [
        'numeric' => '此项 :attribute must be :size.',
        'file'    => '此项 :attribute must be :size kilobytes.',
        'string'  => '此项 :attribute must be :size characters.',
        'array'   => '此项 :attribute must contain :size items.',
    ],
    'string'               => '此项 :attribute must be a string.',
    'timezone'             => '此项 :attribute must be a valid zone.',
    'unique'               => '此项 :attribute 已经存在了.',
    'uploaded'             => '此项 :attribute 上传失败.',
    'url'                  => '此项 :attribute 链接格式是无效的.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using 此项
    | convention "attribute.rule" to name 此项 lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | 此项 following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
    'mobile' => '此项 :attribute 必须是一个有效的手机号.',
    'chinese'=> '此项 :attribute 必须全部是中文.',
    'lenght' => '此项 :attribute 长度必须是 :length 位.',
];
