<?php
/**
 * @see README.md
 */
namespace model\forum;
class Forum_Config extends Forum {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('forum_config');
    }



}