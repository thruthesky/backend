<?php
// create, edit, delete
// list


route()->add('category.create', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'create',
    'variables' => [
        'required' => ['session_id', 'id', 'model', 'model_idx'],
        'optional' => ['name', 'description', 'parent_idx'],
        'system' => []
    ],
    'validator' => function() {
        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;
    }
]);
route()->add('category.edit', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'edit',
    'variables' => [
        'required' => ['session_id', 'id', 'model', 'model_idx' ],
        'optional' => ['name', 'description', 'parent_idx'],
        'system' => []
    ],
    'validator' => function() {
        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;
        if ( empty( in('id') ) ) return ERROR_ID_EMPTY;
        $category = category( in('id') );
        if ( is_error($category) ) return $category;
        if ( ! $category->exist() ) return ERROR_CATEGORY_NOT_EXIST;
        return [ $category ];
    }
]);
route()->add('category.delete', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'delete',
    'variables' => [
        'required' => [ 'id', 'session_id' ],
        'optional' => [],
        'system' => []
    ],
    'validator' => function() {
        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;
        if ( empty( in('id') ) ) return ERROR_ID_EMPTY;
        $category = category( in('id') );
        if ( is_error($category) ) return $category;
        if ( ! $category->exist() ) return ERROR_CATEGORY_NOT_EXIST;
        if ( $category->getChildren() ) return ERROR_CATEGORY_CHILDREN_EXIST;
        return [ $category ];
    }
]);



route()->add( 'category.test', [
    'path' => '\\model\\category\\category_test',
    'method' => 'run',
]);