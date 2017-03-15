<?php

namespace model\file;

class File_Interface extends File {
    public function __construct()
    {
        parent::__construct();

    }

    public function upload(){
        debug_log($_FILES);
        $this->save($_FILES['userfile']);
    }

    
    
    
}
