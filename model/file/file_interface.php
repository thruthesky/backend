<?php

namespace model\file;

class File_Interface extends File {
    public function __construct()
    {
        parent::__construct();

    }

    public function upload(){
        debug_log($_FILES);
        if(!isset($_FILES['userfile'])) return error(ERROR_USERFILE_EMPTY);
        $re = $this->save($_FILES['userfile']);
        if( is_error($re) ) error($re);
        else success( ['idx'=>$re] );
    }

    
    
    
}
