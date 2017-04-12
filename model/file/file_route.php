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
        'optional'=>[ 'type', 'width', 'height', 'quality', 'resize', 'x', 'y', 'enlarge', 'crop', 'xy' ],
        'system'=>[ 'name', 'created' ]
    ],
    'validator' => function () {
        $file = ( new \model\file\File() )->load( in('idx') );
        if ( ! $file->exist() ) {
            debug_log("download::validator, file not exists in Database");
            return ERROR_FILE_NOT_EXIST;
        }
        if ( ! file_exists( $file->path() ) ) {
            debug_log("download::validator, file not exists on HDD");
            return ERROR_FILE_NOT_EXIST_ON_HDD;
        }
        // debug_log($file);
        return [ $file ];
    }
]);

add_route('file.delete',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'delete',
    'variables'=>[
        'required'=>[ 'idx' ],
        'optional'=>[ ],
        'system'=>[ ]
    ],
    'validator' => function() {
        $file = ( new model\file\File() )->load( in('idx' ) );
        if ( $file->user_idx == currentUser()->idx ) {
        }
        else if ( currentUser()->isAdmin() ) {
        }
        else {
            debug_log( $file );
            return ERROR_NOT_YOUR_FILE;
        }
        return [ $file ];
    }
]);



add_route( 'file.list', [
    'path' => "\\model\\file\\file_interface",
    "method" => "search",
    'variables' => [
        'required' => [ 'session_id' ],
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order', 'select', 'page' ],
        'system' => []
    ],
    'validator' => function() {
        if ( ! currentUser()->isAdmin() ) return ERROR_PERMISSION_ADMIN;
    }
]);



add_route('file.test', [
    'path'=> '\\model\\file\\file_test',
    'method'=>'run',
    'variable'=>[
        'required'=>[],
        'optional'=>[],
        'system'=>[]
    ]
]);
//add_route('upload_test', [
//    'path'=> '\\model\\file\\file_test',
//    'method'=>'run',
//    'variable'=>[
//        'required'=>[],
//        'optional'=>[],
//        'system'=>[]
//    ]
//]);