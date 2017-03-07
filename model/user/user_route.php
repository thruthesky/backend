<?php


add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'role' => ['guest']
]);


add_route('user.register', [
    'path' => "\\model\\user\\register",
    'role' => ['guest']
]);


add_route('user.edit', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'edit',
    'role' => ['owner'],
]);

add_route('resign', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'regsign',
    'role' => 'owner'
]);



