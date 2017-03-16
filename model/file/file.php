<?php

namespace model\file;
class File extends \model\entity\Entity
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('file');
    }


    public function save($userfile)
    {
        $src = $userfile['tmp_name'];
        if ( ! file_exists( $src ) ) return ERROR_UPLOAD_FILE_NOT_EXIST;

        $dst = DIR_FILES . '/' . basename($userfile['name']);
        debug_log("move_uploaded_file($src, $dst)");


        /*
        while( file_exists( $uploadfile ) ) {
            $basename = basename( $uploadfile );
            list( $filename, $rest )= explode('.', $basename, 2 );
            if( preg_match('/\((\d+)\)$/', $filename, $ms)){
                debug_log($ms);
                $filename = preg_replace('/\(\d+\)$/', "(" . ($ms[1]+1) . ")"  , $filename );
                $uploadfile = DIR_FILES . '/' . $filename . '.' . $rest;
            }
            else {
                $uploadfile = DIR_FILES . '/' . $filename . '(1).' . $rest;
            }
        }
        */


        if ( file_exists( $dst ) ) return ERROR_UPLOAD_FILE_EXIST;
        if ( is_test() ) $re = @copy( $src, $dst );
        else $re = @move_uploaded_file( $src, $dst );


        if( ! $re ) {
            $error = error_get_last();
            return [ 'code' => ERROR_MOVE_UPLOADED_FILE, 'message' => $error['message'] ];
        }

        $idx = $this->set('name',$userfile['name'])
            ->set('size', $userfile['size'])
            ->set('type',$userfile['type'])
            ->set('name_saved', $dst )
            ->set('model', in('model') )
            ->set('model_idx', in('model_idx') )
            ->set('user_idx', currentUser()->idx )
            ->create();
        return $idx;
    }


}