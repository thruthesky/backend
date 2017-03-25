<?php


$_optional = [
    'password', 'title', 'content',
    'name', 'middle_name', 'last_name', 'email', 'gender', 'birthdate', 'mobile', 'landline', 'contact',
    'country', 'province', 'city', 'address'
];

route()->add( 'post_comment.create', [
    'path' => '\\model\\post\\post_comment_interface',
    'method' => 'create',
    'variables' => [
        'required' => [ 'parent_idx' ],
        'optional' => $_optional
    ],
    'validator' => function() {
        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return [ 'code' => ERROR_PASSWORD_EMPTY, 'message' => "Anonymous must input a password to create a post." ];
        $post = post( in('parent_idx') );
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;
        $config = config()->load( $post->post_config_idx );
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;
        return [ $post, $config ];
    }
]);


route()->add( 'comment.test', [
    'path' => "\\model\\post\\post_comment_test",
    'method' => 'single_test'
]);
