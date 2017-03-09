<?php

add_route( 'forum_post_create', [
    'path' => "\\model\\forum\\forum_post_interface",
    "method" => "create",
    "variables" => [
        'required' => [ 'session_id', 'idx_config' ],
        'optional' => [ 'title', 'content' ],
        'system' => []
    ]
]);
