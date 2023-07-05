<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    |
    */

    'accepted' => ':attribute phải được chấp nhận',
    'active_url' => ':attribute không phải là một URL hợp lệ.',
    'after' => ':attribute phải là một ngày sau :date.',
    'after_or_equal' => ':attribute phải là một ngày sau hoặc bằng :date.',
    'alpha' => ':attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => ':attribute chỉ có thể chứa các chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ':attribute chỉ có thể chứa các chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'before' => ':attribute phải là một ngày trước :date.',
    'before_or_equal' => ':attribute phải là một ngày trước hoặc bằng :date.',
    'between' => [
        'numeric' => ':attribute phải ở giữa :min và :max.',
        'file' => ':attribute phải ở giữa :min và :max kilobytes.',
        'string' => ':attribute phải ở giữa :min  và :max kí tự.',
        'array' => ':attribute phải ở giữa :min và :max mục.',
    ],
    'boolean' => 'Trường :attribute phải có giá trị true hoặc false.',
    'confirmed' => 'Thông tin :attribute không trùng khớp.',
    'date' => ':attribute không hợp lệ.',
    'date_equals' => ':attribute phải là một ngày bằng :date.',
    'date_format' => ':attribute không phù hợp với định dạng :format.',
    'different' => ':attribute  và :other phải khác nhau.',
    'digits' => ':attribute phải là :digits chữ số.',
    'digits_between' => ':attribute phải nằm trong khoảng :min and :max chữ số.',
    'dimensions' => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => 'Trường :attribute có một giá trị trùng lặp.',
    'email' => ':attribute phải là một địa chỉ email hợp lệ.',
    'ends_with' => ':attribute phải kết thúc bằng một trong những điều sau đây :values.',
    'exists' => ':attribute được chọn không hợp lệ.',
    'file' => ':attribute phải là một tệp tin.',
    'filled' => 'Trường :attribute phải có một giá trị.',
    'gt' => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file' => ':attribute phải lớn hơn :value kilobytes.',
        'string' => ':attribute phải lớn hơn :value kí tự.',
        'array' => ':attribute phải nhiều hơn :value mục.',
    ],
    'gte' => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'file' => ':attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => ':attribute phải lớn hơn hoặc bằng :value kí tự.',
        'array' => ':attribute phải có :value mục hoặc nhiều hơn.',
    ],
    'image' => ':attribute phải là hình ảnh.',
    'in' => ':attribute được chọn không hợp lệ.',
    'in_array' => 'Trường :attribute không tồn tại trong :other.',
    'integer' => ':attribute phải là một số nguyên.',
    'ip' => ':attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4' => ':attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json' => ':attribute phải là một chuỗi JSON hợp lệ.',
    'lt' => [
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'file' => ':attribute phải nhỏ hơn :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn :value kí tự.',
        'array' => ':attribute phải nhỏ hơn :value mục.',
    ],
    'lte' => [
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn hoặc bằng :value kí tự.',
        'array' => ':attribute phải nhỏ hơn hoặc bằng :value mục.',
    ],
    'max' => [
        'numeric' => ':attribute có thể không lớn hơn :max.',
        'file' => ':attribute có thể không lớn hơn :max kilobytes.',
        'string' => ':attribute có thể không lớn hơn :max kí tự.',
        'array' => ':attribute có thể không lớn hơnn :max mục.',
    ],
    'mimes' => ':attribute phải là một tệp tin loại :values.',
    'mimetypes' => ':attribute phải là một tệp tin loại :values.',
    'min' => [
        'numeric' => ':attribute phải ít nhất :min.',
        'file' => ':attribute phải ít nhất :min kilobytes.',
        'string' => ':attribute phải ít nhất :min kí tự.',
        'array' => ':attribute phải ít nhất :min mục.',
    ],
    'not_in' => ':attribute được chọn không hợp lệ.',
    'not_regex' => 'Định dạng:attribute không hợp lệ.',
    'numeric' => ':attribute phải là một số.',
    'password' => 'Mật khẩu không đúng.',
    'password_or_username' => 'Mật khẩu hoặc tên người dùng là không chính xác.',
    'present' => 'Trường :attribute phải có mặt.',
    'regex' => 'Định dạng :attribute không hợp lệ.',
    'required' => 'Trường :attribute được yêu cầu.',
    'required_if' => 'Trường:attribute được yêu cầu khi :other là :value.',
    'required_unless' => 'Trường :attribute là bắt buộc trừ khi :other nằm trong :values.',
    'required_with' => 'Trường :attribute được yêu cầu khi :values có mặt.',
    'required_with_all' => 'Trường :attribute được yêu cầu khi :values có mặt.',
    'required_without' => 'Trường :attribute được yêu cầu khi :values không có mặt.',
    'required_without_all' => 'Trường:attribute được yêu cầu khi không có :values hiển thị.',
    'same' => ':attribute và :other phải trùng khớp.',
    'size' => [
        'numeric' => ':attribute phải là :size.',
        'file' => ':attribute phải là :size kilobytes.',
        'string' => ':attribute phải là :size kí tự.',
        'array' => ':attribute phải chứa :size mục.',
    ],
    'starts_with' => ':attribute phải bắt đầu với một trong những điều sau đây: :values.',
    'string' => ':attribute phải là một chuỗi kí tự.',
    'timezone' => ':attribute phải là một vùng hợp lệ.',
    'unique' => ':attribute đã được thực hiện.',
    'uploaded' => ':attribute không thể tải lên.',
    'url' => 'Định dạng :attribute không hợp lệ.',
    'uuid' => ':attribute phải là một UUID hợp lệ.',

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

    'captcha' => 'Mã xác thực không đúng...',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
