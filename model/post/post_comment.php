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



    public function pre( $option = [] ) {

        $ret = [];
        $ret['idx'] = $this->idx;
        $ret['root_idx'] = $this->root_idx;         // root idx is needed to insert the comment into post lists.
        $ret['parent_idx'] = $this->parent_idx;
        $ret['depth'] = $this->depth;
        $ret['content'] = $this->content;
        $ret['files'] = $this->file()->get();
        $ret['vote_good'] = $this->vote_good;
        $ret['vote_bad'] = $this->vote_bad;
        $user = user( $this->user_idx );
        //debug_log('user: ');
        //debug_log($user);
        if ( $user->exist() ) {
            $u['idx'] = $user->idx;
            $u['id'] = $user->id;
            $u['name'] = $user->name;
            $u['url_primary_photo'] = $user->getPrimaryPhotoUrl();
            $ret['user'] = $u;
        }

        return $ret;
    }

}
