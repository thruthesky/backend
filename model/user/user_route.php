<?php


add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'variables' => [
        'required' => [ 'id', 'password' ],
        'optional' => [ 'domain', 'name', 'middle_name', 'last_name',
            'nickname', 'email', 'gender', 'birth_year', 'birth_month', 'birth_day', 'landline',
            'mobile', 'address', 'country', 'province', 'city', 'zipcode',
            'meta'
        ],
        'system' => [ 'route' ]
    ]
]);


add_route('user.edit', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'edit',
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [ 'domain', 'name', 'middle_name', 'last_name',
            'nickname', 'email', 'gender', 'birth_year', 'birth_month', 'birth_day', 'landline',
            'mobile', 'address', 'country', 'province', 'city', 'zipcode',
            'meta'
        ],
        'system' => [ 'route' ]
    ]
]);


add_route('user.get', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'get',
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [],
        'system' => [ 'route' ]
    ]
]);


add_route('resign', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'regsign'
]);



