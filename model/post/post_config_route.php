<?php

$_optional = [
    'name', 'description', 'moderators',
    'level_list', 'level_view', 'level_write', 'level_comment'
];

add_route('post_config.create', [
    'path' => "\\model\\post\\post_config_interface",
    'method' => 'create',
    'variables' => [
        'required' => [ 'session_id', 'id' ],
        'optional' => $_optional,
        'system' => []
    ]
]);


add_route('post_config.delete', [
    'path' => "\\model\\post\\post_config_interface",
    'method' => 'delete',
    'variables' => [
        'required' => [ 'session_id', 'id' ],
        'optional' => [],
        'system' => []
    ],
    'validator' => function() {

        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;
        $config = config()->load(in('id'));
        if( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;
        return [ $config ];

    }
]);


add_route('post_config.edit', [
    'path' => "\\model\\post\\post_config_interface",
    'method' => 'edit',
    'variables' => [
        'required' => [ 'session_id', 'id' ],
        'optional' => $_optional,
        'system' => []
    ]
]);


add_route('post_config.data', [
    'path' => "\\model\\post\\post_config_interface",
    'method' => 'data',
    'variables' => [
        'required' => [  ],
        'optional' => [ ],
        'system' => [ 'idx', 'id' ]        // it gets one of 'idx' or 'id'
    ],
    'validator' => function () {
        if ( in('idx') ) $config = config( in('idx') );
        else if ( in('id') ) $config = config( in('id') );
        else return ERROR_REQUIRED_INPUT_IS_MISSING;
        if ( is_error( $config ) ) return $config;
        if( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;
        return [ $config ];
    }
]);


add_route( 'post_config.list', [
    'path' => "\\model\\post\\post_config_interface",
    "method" => "search",
    'variables' => [
        'required' => [],
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order' ],
        'system' => [ 'session_id' ]
    ]
]);


route()->add('config.test', [
    'path' => '\\model\\post\\post_config_test',
    'method' => 'run'
]);
