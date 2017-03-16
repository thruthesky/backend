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

        if ( ! isset($userfile['error'])  ) return ERROR_UPLOAD_ERROR_NOT_SET;
        debug_log($userfile);
        if ( $userfile['error'] === UPLOAD_ERR_OK && $userfile['size'] && $userfile['tmp_name'] ) {
            // uploading from web-browser to web-server was successfully done.
            // continue your work.
        } else {
            return $this->errorResponse( $userfile['error'] );
        }

        $src = $userfile['tmp_name'];
        if ( ! file_exists( $src ) ) return ERROR_UPLOAD_FILE_NOT_EXIST;




        $idx = $this->set('name',$userfile['name'])
            ->set('size', $userfile['size'])
            ->set('type',$userfile['type'])
            ->set('model', in('model') )
            ->set('model_idx', in('model_idx') )
            ->set('user_idx', currentUser()->idx )
            ->create();

        if ( is_error($idx) ) return ERROR_FILE_UPLOAD_CREATE_IDX_FAILED;



        $dst = DIR_FILES . "/$idx";
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

        $idx = $this->load($idx)->update( ['name_saved'])
        return $idx;
    }

    private function errorResponse($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $code = ERROR_UPLOAD_ERR_INI_SIZE;
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $code = ERROR_UPLOAD_ERR_FORM_SIZE;
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $code = ERROR_UPLOAD_ERR_PARTIAL;
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $code = ERROR_UPLOAD_ERR_NO_FILE;
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $code = ERROR_UPLOAD_ERR_NO_TMP_DIR;
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $code = ERROR_UPLOAD_ERR_CANT_WRITE;
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $code = ERROR_UPLOAD_ERR_EXTENSION;
                $message = "File upload stopped by extension";
                break;

            default:
                $code = ERROR_UNKNOWN;
                $message = "Unknown upload error";
                break;
        }
        return [ 'code' => $code, 'message' => $message ];
    }

}