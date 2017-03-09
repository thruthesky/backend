<?php

add_route('install', [
    'path' => "\\model\\system\\install",
    'vars' => []
]);



add_route('version', [
    'path' => "\\model\\system\\system_interface",
    'method' => 'version',
    'variables' => [
        'system' => [ 'route' ]
    ]
]);



add_route('system.error', [
    'path' => "\\model\\system\\system_interface",
    'method' => 'error'
]);
add_route('system.scriptError', [
    'path' => "\\model\\system\\system_interface",
    'method' => 'scriptError'
]);
add_route('system.internalServerError', [
    'path' => "\\model\\system\\system_interface",
    'method' => 'internalServerError'
]);

add_route('system.timeoutError', [
    'path' => "\\model\\system\\system_interface",
    'method' => 'timeoutError'
]);

add_route('system.routeMethodError', [
    'path' => "\\model\\system\\system_interface",
    "method" => "No_Method_Exist",
    "variables" => [
        "required" => [ "name" ],
        "optional" => [ "age" ],
        "system" => [ "route" ]
    ]
]);

add_route('system.routeRequiredError', [
    'path' => "\\model\\system\\system_interface",
    "method" => "version",
    "variables" => [
        "required" => [ 'name' ],
        "optional" => [ "age" ],
        "system" => []
    ]
]);
