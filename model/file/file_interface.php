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

    /**
     * @param File $file
     */
    public function download( $file ) {

        $file->increaseNoOfDownload();
        $idx = $file->idx;

        $file_path = DIR_UPLOAD . '/' . $idx;


        $type = in('type');
        $width = in('width');
        $height = in('height');
        $quality = in('quality');
        $resize = in('resize');
        $allow_enlarge = in('enlarge') == 'Y' ? TRUE : FALSE;

        $x = in('x');
        $y = in('y');



        if ( !$type && !$width && !$height ) return $this->send( $file_path );

        if ( empty($resize) ) $resize = 'resize';


        $cache_file_path = DIR_CACHE . "/$idx-$resize-$x-$y-{$width}x{$height}-$quality.$type";

        if ( file_exists( $cache_file_path ) ) {
            debug_log("cache file path exists: $cache_file_path, so it just return cache file.");
            return $this->send( $cache_file_path );
        }

        $image = new ImageResize( $file_path );


        // resize
        if ( $width || $height ) {
            if ( $resize == 'resize' ) $image->resize( $width, $height, $allow_enlarge);
            else if ( $resize == 'best-fit' ) $image->resizeToBestFit( $width, $height, $allow_enlarge );
            else if ( $resize == 'one-dimension' ) {
                if ( $width ) $image->resizeToWidth( $width, $allow_enlarge );
                else if ( $height ) $image->resizeToHeight( $height, $allow_enlarge );
            }
            else if ( $resize == 'crop' ) $image->crop( $width, $height );
            else if ( $resize == 'freecrop' ) $image->freecrop( $width, $height, $x, $y );
        }
        



        // quality.
        if ( $type && $quality ) {
            if ( $type == 'jpg' ) $image->quality_jpg = $quality;
            else if ( $type == 'png' ) $image->quality_png = $quality;
        }


        //

        // save
        if ( $type == 'jpg' ) $image->save( $cache_file_path, IMAGETYPE_JPEG);
        else if ( $type == 'png' ) $image->save( $cache_file_path, IMAGETYPE_PNG);
        else $image->save( $cache_file_path );
        debug_log("cache file generated on : $cache_file_path");


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
