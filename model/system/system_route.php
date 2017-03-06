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


