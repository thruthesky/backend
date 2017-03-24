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
        'system' => [ 'route', 'file_hooks' ]
    ],
    'validator' => function() {
        if ( currentUser()->logged() ) return ERROR_USER_LOGGED_IN;
        return OK;
    }
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
        'system' => [ 'id' ]
    ]
]);


add_route('login', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'login',
    'variables' => [
        'required' => [ 'id', 'password' ],
        'optional' => [ ],
        'system' => [ ]
    ]
]);

add_route('logout', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'logout',
    'variables' => [
        'required' => [ 'session_id' ]
    ]
]);


add_route('resign', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'resign',
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [ ],
        'system' => [ 'route' ]
    ]
]);


add_route('user.data', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'data',
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [],
        'system' => [ 'id' ]
    ]
]);



add_route( 'user.list', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'search',
    'variables' => [
        'required' => [],
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order' ],
        'system' => [ 'session_id' ]
    ]
]);


add_route('user.test.search', [
    'path' => "\\model\\user\\user_test",
    'method' => 'search'
]);

add_route('user.test', [
    'path' => "\\model\\user\\user_test",
    'method' => 'run'
]);

