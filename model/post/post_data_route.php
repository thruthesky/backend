<?php

$_optional = [
    'password', 'title', 'content', 'parent_idx',
    'name', 'middle_name', 'last_name', 'email', 'gender', 'birthdate', 'mobile', 'landline', 'contact',
    'country', 'province', 'city', 'address'
];

add_route( 'post_data.create', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "create",
    "variables" => [
        'required' => [ 'post_config_id' ],
        'optional' => $_optional,
        'system' => [ 'session_id', 'post_config_id', 'file_hooks' ]
    ],
    'validator' => function () {
        $config = config()->load( in( 'post_config_id' ) );
        if( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;
        return OK;
    }
]);

add_route( 'post_data.edit', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "edit",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => $_optional,
        'system' => [ 'session_id', 'file_hooks' ]
    ]
]);


add_route( 'post_data.delete', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "delete",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => [ 'session_id', 'password' ],
        'system' => []
    ]
]);


add_route( 'post_data.data', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "data",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => [ ],
        'system' => []
    ]
]);

add_route( 'post_data.list', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "search",
    'variables' => [
        'required' => [],
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order', 'select', 'extra' ],
        'system' => [ 'session_id' ]
    ]
]);


add_route( 'post.test', [
    'path' => "\\model\\post\\post_data_test",
    'method' => 'single_test'
]);