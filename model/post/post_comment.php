<?php
/**
 * @see README.md
 */
namespace model\post;


class Post_Comment extends Post_Data
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('post_data');
    }



}
