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
        $idx = $this->save( $_REQUEST, $_FILES['userfile'] );
        if( is_error($idx) ) error( $idx );
        else {
            success( $this->reset( $idx )->pre() );
        }
    }

    public function download() {

        $this->load( in('idx') );
        if ( ! $this->exist() ) return error( ERROR_FILE_NOT_EXIST );
        $this->increaseNoOfDownload();

        $file_path = DIR_UPLOAD . '/' . $this->idx;


        $type = in('type');
        $width = in('width');
        $height = in('height');
        $quality = in('quality');

        if ( !$type && !$width && !$height ) return $this->send( $file_path );


        $cache_file_path = DIR_CACHE . "/{$this->idx}-$quality.$type";

        if ( file_exists( $cache_file_path ) ) {
            debug_log("cache file path exists: $cache_file_path, so it just return cache file.");
            return $this->send( $cache_file_path );
        }

        $image = new ImageResize( $file_path );



        if ( $type && $quality ) {
            if ( $type == 'jpg' ) $image->quality_jpg = $quality;
            else if ( $type == 'png' ) $image->quality_png = $quality;
        }

        if ( $type == 'jpg' ) $image->save( $cache_file_path, IMAGETYPE_JPEG);
        else if ( $type == 'png' ) $image->save( $cache_file_path, IMAGETYPE_PNG);


        return $this->send( $cache_file_path );


    }


    public function send( $cache_file_path ) {
        {
            $image = new ImageResize( $cache_file_path );
            $image->output();
            return;
        }
    }


    /**
     *
     * @change: Don't do 'file not exist check' here.
     *
     * @param File $file
     * @return mixed
     */
    public function delete( $file = null ) {
        //$this->load( in('idx') );


        //$re = parent::delete();

        $re = $file->delete();

        if ( is_success( $re ) ) success( ['idx'=>in('idx')] );
        else error( $re );


    }
}
