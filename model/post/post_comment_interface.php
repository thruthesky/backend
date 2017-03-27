<?php

namespace model\post;
class Post_Comment_Interface extends Post_Comment
{

    /**
     *
     * @param Post_Data|Post_Comment $post
     * @param Post_Config $config
     * @return mixed
     *
     */
    public function create( $post=null, $config=null ) // for variable compatibilities of parent.
    {

        // post info.
        $record = route()->get_route_optional_variables();


        if ( $post->parent_idx == 0 ) {             // the post is a root post. it will be a child ( not decendant )
            $record['root_idx'] = $post->idx;
        }
        else {                                      // the post is a comment of a root post or a comment. it is a decentant.
            $record['root_idx'] = $post->root_idx;
            $record['depth'] = $post->depth + 1;
        }
        $record['parent_idx'] = $post->idx;

        //
        $record['user_idx'] = currentUser()->idx;
        $record['post_config_idx'] = $config->idx;



        // password.
        if ( in( 'password' ) ) $record['password'] = $this->encryptPassword( in('password') );

        // extra info.
        $record['ip'] = $_SERVER['REMOTE_ADDR'];
        $record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


        // update order_no

        $table = $this->getTable();

        if ( $post->parent_idx == 0 ) { // new comment of root.
            $my_order_no = db()->result("SELECT MAX(order_no) FROM $table WHERE root_idx=$record[root_idx]");
        }
        else {
            $children = $this->getChildren( $post->idx );
            if ( $children ) {
                $last_idx = array_pop( $children );
                $cmt = $this->load( $last_idx );
                $my_order_no = $cmt->order_no;
            }
            else $my_order_no = $post->order_no;

            //
            db()->query("UPDATE $table SET order_no=order_no+1 WHERE root_idx=$record[root_idx] AND order_no>$my_order_no");
        }


        $record['order_no'] = $my_order_no + 1;



        // create
        $comment_idx = parent::create( $record );

        if ( is_error( $comment_idx ) ) return error( $comment_idx );

        $comment = comment( $comment_idx );
        $re_upload = $comment->hookUpload( $comment ); if ( is_error( $re_upload ) ) return error( $re_upload );


        return success( [ 'idx' => $comment_idx ] );

    }

    /**
     * @param Post_Comment $comment
     * @param Post_Config $config
     * @return mixed
     */
    public function edit( $comment, $config ) {

        $record = route()->get_route_optional_variables();

        unset( $record['password'] ); // no need to set password again.

        $re = $comment->update($record);

        if ( is_success( $re ) ) {
            $re_upload = $comment->hookUpload( $comment ); if ( is_error( $re_upload ) ) return error( $re_upload );
            success( ['idx'=> $this->idx] );
        }
        else error( ERROR_DATABASE_UPDATE_FAILED ); // should not happened.

    }


    /**
     *
     * This does not actually delete post. it just mark as delete. it does not delete meta data of the post also.
     *
     * @param Post_Comment $comment
     * @param Post_Config $config
     * @return mixed
     */
    public function delete( $comment=null, $config=null ) {
        $re = $comment->delete();
        if ( is_success($re) ) success( ['idx' => in('idx') ]);
        else error( $re );

    }

}
