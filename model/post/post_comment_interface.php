<?php

namespace model\post;
class Post_Comment_Interface extends Post_Comment
{

    /**
     *
     * @param Post_Data $post
     * @param Post_Config $config
     * @return mixed
     *
     */
    public function create( $post=null, $config=null ) // for variable compatibilities.
    {

        // post info.
        $record = route()->get_route_optional_variables();
        $record['parent_idx'] = $post->idx;
        $record['user_idx'] = currentUser()->idx;
        $record['post_config_idx'] = $config->idx;


        // password.
        if ( in( 'password' ) ) $record['password'] = $this->encryptPassword( in('password') );

        // extra info.
        $record['ip'] = $_SERVER['REMOTE_ADDR'];
        $record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        // create
        $comment_idx = parent::create( $record );

        if ( is_error( $comment_idx ) ) return error( $comment_idx );


        return success( [ 'idx' => $comment_idx ] );

    }
}
