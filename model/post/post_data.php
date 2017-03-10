<?php
/**
 * @see README.md
 */
namespace model\post;
class Post_Data extends Post {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('forum_data');
    }


}
