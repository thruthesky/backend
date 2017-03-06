<?php


$routes['user.register'] = [
    'path' => "\\model\\user\\register",
    'role' => 'guest'
];

$routes['user.edit'] = [
    'path' => "\\model\\user\\edit",
    'role' => 'owner'
];

$routes['user.resign'] = [
    'path' => "\\model\\user\\resign"
];


