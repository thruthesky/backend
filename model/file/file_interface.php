<?php

namespace model\file;

include_once __ROOT_DIR__ . '/etc/external/ImageResize.php';
use \Eventviva\ImageResize;

class File_Interface extends File {
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return mixed
     */
    public function upload() {
        debug_log($_FILES);
        if(!isset($_FILES['userfile'])) return error(ERROR_USERFILE_EMPTY);
        $re = $this->save( $_REQUEST, $_FILES['userfile'] );
        if( is_error($re) ) error($re);
        else success( ['idx'=>$re] );
    }

    public function download() {

        $this->load( in('idx') );
        if ( ! $this->exist() ) return error( ERROR_FILE_NOT_EXIST );
        $this->increaseNoOfDownload();


        $image = new ImageResize( DIR_UPLOAD . '/' . $this->idx );

        $image->output();


    }

    
    
    public function delete( $_ = null ) {
        $this->load( in('idx') );

        if ( ! $this->exist() ) return error( ERROR_FILE_NOT_EXIST );
        $re = parent::delete();

        if ( is_success( $re ) ) success( ['idx'=>in('idx')] );
        else error( $re );


    }
}
