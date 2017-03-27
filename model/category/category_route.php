<?php
// create, edit, delete
// list


route()->add('category.create', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'create',
    'variables' => [
        'required' => ['id', 'model', 'model_idx'],
        'optional' => ['name', 'description', 'parent_idx'],
        'system' => []
    ]
]);
route()->add('category.edit', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'create',
    'variables' => [
        'required' => [],
        'optional' => [],
        'system' => []
    ]
]);
route()->add('category.delete', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'create',
    'variables' => [
        'required' => [],
        'optional' => [],
        'system' => []
    ]
]);
route()->add('category.list', [
    'path' => '\\model\\category\\category_interface',
    'method' => 'create',
    'variables' => [
        'required' => [],
        'optional' => [],
        'system' => []
    ]
]);

route()->add( 'category.test', [
    'path' => '\\model\\category\\category_test',
    'method' => 'run',
]);