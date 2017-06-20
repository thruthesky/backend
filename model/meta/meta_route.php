<?php

route()->add('meta.test', [
    'path' => '\\model\\meta\\meta_test',
    'method' => 'run'
]);

route()->add('meta.create', [
    'path' => "\\model\\meta\\meta_interface",
    'method' => 'create',
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [ 'model', 'model_idx', 'code', 'data' ],
        'system' => []
    ],
    'validator' => function() {

        if ( ! in('model') ) return ERROR_MODEL_IS_EMPTY;
        if ( ! in('model_idx') ) return ERROR_MODEL_IDX_IS_EMPTY;
        if ( ! in('code') ) return ERROR_CODE_IS_EMPTY;

        $user = currentUser();
        if ( is_error( $user ) ) return $user;
        if ( ! $user->logged() ) return ERROR_USER_NOT_LOGIN;
        return;
    }
]);



route()->add('meta.delete', [
    'path' => "\\model\\meta\\meta_interface",
    'method' => 'delete',
    'variables' => [
        'required' => [ 'session_id', 'idx' ],
        'optional' => [],
        'system' => []
    ],
    'validator' => function() {

        $user = currentUser();
        if ( is_error( $user ) ) return $user;
        if ( ! $user->logged() ) return ERROR_USER_NOT_LOGIN;

        if ( ! in('idx') ) return ERROR_IDX_EMPTY;
        $meta = meta( in('idx') );
        if( ! $meta->exist() ) return ERROR_META_NOT_EXIST;

        if ( currentUser()->idx != $meta->user_idx ) return ERROR_NOT_YOUR_META;

        return [ $meta ];

    }
]);




route()->add( 'meta.list', [
    'path' => "\\model\\meta\\meta_interface",
    "method" => "search",
    'variables' => [
        'required' => [ 'where', 'bind' ],
        'optional' => [ 'from', 'limit', 'order', 'page' ],
        'system' => [ 'session_id' ]
    ],
    'validator' => function() {

    }
]);


route()->add('meta.config', [
    'path' => '\\model\\meta\\meta_interface',
    'method' => 'config',
    'variables' => [

    ]
]);



