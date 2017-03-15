<?php

add_route('upload',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'upload',
    'variables'=>[
        'required'=>['model','model_idx'],
        'optional'=>['code'],
        'system'=>['session_id']
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