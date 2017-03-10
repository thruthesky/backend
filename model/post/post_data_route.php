<?php

add_route( 'post.create', [
    'path' => "\\model\\forum\\post_data_interface",
    "method" => "create",
    "variables" => [
        'required' => [ 'session_id', 'idx_config' ],
        'optional' => [ 'title', 'content' ],
        'system' => []
    ]
]);
