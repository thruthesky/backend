<?php

namespace model\file;
class File extends \model\entity\Entity
{
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('file');
    }

    
    public function save($userfile){
        
    }
}