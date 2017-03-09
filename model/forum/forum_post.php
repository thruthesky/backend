<?php
/**
 * @see README.md
 */
namespace model\forum;
class Forum_Post extends Forum {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('forum_data');
    }


}
