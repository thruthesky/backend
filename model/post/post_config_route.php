<?php
add_route('post_config.create', [
    'path' => "\\model\\post\\post_config_interface",
    'method' => 'create',
    'variables' => [
        'required' => [ 'id', 'name', 'description' ],
        'optional' => [ 'level_list', 'level_view', 'level_write', 'level_comment' 
        ],
        'system' => [ 'route' ]
    ]
]);