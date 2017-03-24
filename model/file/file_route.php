<?php

add_route('upload',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'upload',
    'variables'=>[
        'required'=>[],
        'optional'=>[ 'model', 'model_idx', 'code', 'finish' ],
        'system'=>['session_id', 'unique']
    ]
]);



add_route('download',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'download',
    'variables'=>[
        'required'=>[ 'idx' ],
        'optional'=>[ 'width', 'height', 'quality', 'resize' ],
        'system'=>[ ]
    ]
]);

add_route('file.delete',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'delete',
    'variables'=>[
        'required'=>[ 'idx' ],
        'optional'=>[ ],
        'system'=>[ ]
    ]
]);


add_route('upload_test', [
    'path'=> '\\model\\file\\file_test',
    'method'=>'run',
    'variable'=>[
        'required'=>[],
        'optional'=>[],
        'system'=>[]
    ]
]);