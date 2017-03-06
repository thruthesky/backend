<?php


add_route('user.register', [
    'path' => "\\model\\user\\register",
    'role' => 'guest'
]);


add_route('user.edit', [
    'path' => "\\model\\user\\edit",
    'role' => 'owner',
    'security' => function() {

    }
]);

add_route('user.resign', [
    'path' => "\\model\\user\\resign"
]);



