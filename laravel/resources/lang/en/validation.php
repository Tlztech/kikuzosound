<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
    
    //r-mail-form validation

    'mail_required'     => 'Please enter your e-mail address',
    'password_required' => 'Enter your password',
    'group_required'    => 'Please enter your work / school name',
    'group_max'         => 'Enter your work / school name within 50 characters',
    'name1_required'    => 'Please enter your last name',
    'name1_max'         => 'Last name must not exceed 50 characters',
    'name2_required'    => 'Please enter your first name',
    'name2_max'         => 'Name must be less than 50 characters',
    'kana1_required'    => 'Please enter your last name (Hiragana)',
    'kana1_max'         => 'Please enter your last name (Hiragana) within 50 characters',
    'kana2_required'    => 'Please enter your first name (Hiragana)',
    'kana2_max'         => 'Please enter your first name (Hiragana) within 50 characters',
    'mail_email'        => 'Please enter a valid email address',
    'mail_max'          => 'Email must be less than 200 characters',
    'password_regex'    => 'Password must be between 5 and 30 alphanumeric characters',
    'gender_required'   => 'Please select a gender',
    'tel_min'           => 'Invalid phone number',
    'tel_max'           => 'Invalid phone number',
    'tel_digits_between'         => 'Invalid phone number',
    'tel_integer'       => 'Please enter a phone number',


    //contact-form validation
    
    'facility_name_required' => 'Please enter the facility name',
    'facility_name_length' => 'Please enter the facility name within 50 characters',
    'name_required' => 'Please enter your name',
    'name_length' => 'Please enter your name within 50 characters',
    'email_required' => 'Please enter your e-mail address',
    'valid_email' => 'Please enter a valid email address',
    'phone_required' => 'Please enter a phone number',
    'question_max' => 'Please enter inquiries within 500 characters',
    'question_regex' => 'HTML cannot be entered in the inquiry content',


    //application-form validation

    'affiliation_required' => 'Please enter your affiliation',
    'application_max' => 'Enter your affiliation within 100 characters',
    'zip_required' => 'Please enter a zip code',
    'address_required' => 'Please enter your address',
    'address_max' => 'Address must not exceed 200 characters',
    'dealer_max' => 'Sales Company Name must not exceed 100 characters',
    'area_max' => 'Branch / Sales Office must not exceed 100 characters',
    'agree_required' => 'Please check the box above if you want to purchase',
    'kind_max' => 'Coupon code cannot be more than 100 characters in length',

];
