<?php


add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'variables' => [
        'required' => [ 'id', 'password', 'name'],
        'optional' => [ 'mobile' ]
    ]
]);


add_route('user.edit', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'edit'
]);

add_route('resign', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'regsign'
]);



