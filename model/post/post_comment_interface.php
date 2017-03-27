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


        if ( $post->parent_idx == 0 ) {             // the post is a root post.
            $record['root_idx'] = $post->idx;
        }
        else {                                      // the post is a comment of a root post.
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
            //$my_order_no = $post->order_no;
//            $my_order_no = db()->result("SELECT MAX(order_no) FROM $table WHERE root_idx=$record[root_idx] AND parent_idx=$record[parent_idx]");
//            $my_order_no = $my_order_no ? $my_order_no : $post->order_no;

            $children_idx = $this->getChildren( $post->idx );
            if ( $children_idx ) {
                $last_idx = array_pop( $children_idx );
                $cmt = $this->load( $last_idx );
                $my_order_no = $cmt->order_no;
            }
            else $my_order_no = $post->order_no;

            //
            db()->query("UPDATE $table SET order_no=order_no+1 WHERE root_idx=$record[root_idx] AND order_no>$my_order_no");
        }

        // 여기서 부터.
        // 자식들을 재귀 함수로 구하는 함수를 만들어서 현재 코멘트(의 코멘트)의 맨 아래 order no 를 구해서 + 1 을 해야 한다.


//        $my_order_no = db()->result("SELECT MAX(order_no) FROM $table WHERE root_idx=$record[root_idx] AND parent_idx=$record[parent_idx]");
//        if ( $my_order_no ) {
//            $q = "UPDATE $table SET order_no=order_no+1 WHERE root_idx=$record[root_idx] AND order_no>$my_order_no";
//            db()->query( $q );
//        }
//        else {
//            $my_order_no = db()->result("SELECT MAX(order_no) FROM $table WHERE root_idx=$record[root_idx]");
//        }

        $record['order_no'] = $my_order_no + 1;


//
//        $table = $this->getTable();
//
//        if ( $post->parent_idx == 0 ) { // new comment of root.
//            $order_no = db()->result("SELECT MAX(order_no) FROM $table WHERE root_idx=$record[root_idx]");
//
//        }
//        else {
//            $order_no = $post->order_no;
//            $q = "UPDATE $table SET order_no=order_no+1 WHERE root_idx=$record[root_idx] AND order_no>$order_no";
//            db()->query( $q );
//        }
//        $record['order_no'] = $order_no + 1;


        // create
        $comment_idx = parent::create( $record );

        if ( is_error( $comment_idx ) ) return error( $comment_idx );


        return success( [ 'idx' => $comment_idx ] );

    }
}
