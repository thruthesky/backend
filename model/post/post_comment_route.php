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
        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return [ 'code' => ERROR_PASSWORD_EMPTY, 'message' => "Anonymous must input a password to create a comment." ];
        $post = post( in('parent_idx') );
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;
        $config = config()->load( $post->post_config_idx );
        if ( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;
        return [ $post, $config ];
    }
]);



route()->add( 'post_comment.edit', [
    'path' => '\\model\\post\\post_comment_interface',
    'method' => 'edit',
    'variables' => [
        'required' => [ 'idx' ],
        'optional' => $_optional
    ],
    'validator' => function() {
        $comment = comment( in('idx') );
        if ( is_error( $comment ) ) return $comment;
        if ( ! $comment->exist() ) return ERROR_COMMENT_NOT_EXIST;

        $config = config()->load( $comment->post_config_idx );
        if ( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;

        if ( $re = $comment->editPermission() ) return $re;

        return [ $comment, $config ];
    }
]);



route()->add( 'post_comment.delete', [
    'path' => '\\model\\post\\post_comment_interface',
    'method' => 'delete',
    'variables' => [
        'required' => [ 'idx' ]
    ],
    'validator' => function() {
        $comment = comment( in('idx') );
        if ( is_error( $comment ) ) return $comment;
        if ( ! $comment->exist() ) return ERROR_COMMENT_NOT_EXIST;

        $config = config()->load( $comment->post_config_idx );
        if ( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;

        if ( $re = $comment->deletePermission() ) return $re;
        return [ $comment, $config ];
    }
]);

route()->add( 'comment.test', [
    'path' => "\\model\\post\\post_comment_test",
    'method' => 'single_test'
]);
