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
        'system' => [ 'route', 'file_hooks', 'birthday' ]
    ],
    'validator' => function() {
        $user = currentUser();
        if ( is_error( $user ) ) return $user;
        if ( $user->logged() ) return ERROR_USER_LOGGED_IN;
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
        'system' => [ 'id', 'birthday' ]
    ],
    'validator' => function() {
    }
]);



add_route( 'user.delete', [
    'path' => "\\model\\user\\user_interface",
    "method" => "delete",
    "variables" => [
        'required' => [ 'session_id', 'id' ],
        'optional' => [ ],
        'system' => []
    ],
    'validator' => function() {
        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;

        $user = user( in('id') );
        if ( ! $user->exist() ) return ERROR_USER_NOT_EXIST;

        return OK;
    }
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
    ],
    'validator' => function () {
        $user = user()->load( in('session_id') );
        if ( ! $user->exist() ) return ERROR_WRONG_SESSION_ID;
    }
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
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order', 'page' ],
        'system' => [ 'session_id' ]
    ]
]);


add_route('user.test.search', [
    'path' => "\\model\\user\\user_test",
    'method' => 'search'
]);

add_route('user.test', [
    'path' => "\\model\\user\\user_test",
    'method' => 'single_test'
]);

route()->add('user.test_admin_change_user_password', [
    'path' => "\\model\\user\\user_test",
    'method' => 'adminChangeUserPassword'
]);



route()->add('change_password', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'changePassword',
    'variables' => [
        'required' => ['session_id', 'old_password', 'new_password']
    ],
    'validator' => function () {
        if ( ! in('old_password') ) return ERROR_OLD_PASSWORD_IS_MISSING;
        if ( ! in('new_password') ) return ERROR_NEW_PASSWORD_IS_MISSING;
        $user = currentUser();
        if ( is_error( $user ) ) return $user;
        if ( ! $user->logged() ) return ERROR_USER_NOT_LOGIN;
        //debug_log($user->getRecord());
        if ( ! $user->checkPassword( in('old_password'), $user->password ) ) return ERROR_WRONG_PASSWORD;
        return [ $user ];
    }
]);



route()->add('admin_change_user_password', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'adminChangeUserPassword',
    'variables' => [
        'required' => ['session_id', 'user_idx', 'new_password']
    ],
    'validator' => function () {
        if ( ! in('user_idx') ) return ERROR_USER_IDX_EMPTY;
        if ( ! in('new_password') ) return ERROR_NEW_PASSWORD_IS_MISSING;
        $admin = currentUser();
        if ( is_error( $admin ) ) return $admin;
        if ( ! $admin->isAdmin() ) return ERROR_PERMISSION_ADMIN;

        $user = user()->load( in('user_idx') );
        if ( is_error( $user ) ) return $user;
        return [ $user ];
    }
]);
