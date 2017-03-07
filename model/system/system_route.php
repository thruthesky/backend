<?php

add_route('install', [
    'path' => "\\model\\system\\install",
    'vars' => []
]);






add_route('version', [
    'path' => "\\model\\system\\system",
    'method' => 'version',
    'vars' => []
]);





add_route('system.error', [
    'path' => "\\model\\system\\system",
    'method' => 'error'
]);
add_route('system.scriptError', [
    'path' => "\\model\\system\\system",
    'method' => 'scriptError'
]);
add_route('system.internalServerError', [
    'path' => "\\model\\system\\system",
    'method' => 'internalServerError'
]);




add_route('system.timeoutError', [
    'path' => "\\model\\system\\system",
    'method' => 'timeoutError'
]);



