<?php
namespace model\post;
use model\test\Test;

class Post_Comment_Test extends Test {

    private  $test_config_id;

    public function single_test() {
        parent::$reload = 5;
        $this->run();
    }
    public function run() {

        $this->test_config_id = 'forum-comment-test';
//        $re = $this->route('post_config.create', [ 'id' => $this->test_config_id, 'session_id' => $this->getAdminSessionId() ]);
//        if ( is_error($re) ) test( $re, '' . get_error_string($re) );

        $this->createPostConfig( $this->test_config_id );

        $this->crud();
        $this->tree_test();



        config( $this->test_config_id )->delete();
    }

    public function crud() {

        // create a post
        $re = $this->route( 'post_data.create', [
            'post_config_id' => $this->test_config_id,
            'title' => 'hello',
            'password' => '1234a'] );

        $parent_idx = $re['data']['idx'];


        // create a comment.
        $param = [
            'parent_idx' => $parent_idx,
            'content' => 'one'  ];

        // empty password error.
        $re = $this->route( 'post_comment.create', $param );
        test( is_error($re) == ERROR_PASSWORD_EMPTY, " " . get_error_string($re) );

        $param['password'] = '1234a';
        $re = $this->route( 'post_comment.create', $param );
        test( is_success($re), "comment create: " . get_error_string($re) );

        $comment_idx = $re['data']['idx'];


        $edit = [
            'idx' => $comment_idx,
            'content' => 'edited',
            'password' => '1234abc'
        ];
        $re = $this->route('post_comment.edit', $edit);
        test( is_error($re) == ERROR_WRONG_PASSWORD, "comment edit: " . get_error_string($re) );


        $edit['password'] = '1234a';
        $re = $this->route('post_comment.edit', $edit);
        test( is_success($re), "comment edit: " . get_error_string($re) );



        $param = [
            'parent_idx' => $parent_idx,
            'content' => 'user',
            'session_id' => testUser()->getSessionId()
        ];
        $re = $this->route('post_comment.create', $param);
        test( is_success($re), "comment create by test user: " . get_error_string($re) );
        $comment_idx = $re['data']['idx'];


        $param = [
            'idx' => $comment_idx,
            'content' => 'updated'
        ];
        $re = $this->route('post_comment.edit', $param);
        test( is_error($re) == ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS, "comment edit: " . get_error_string($re) );


        $param = [
            'idx' => $comment_idx,
            'session_id' => thruthesky()->getSessionId(),
            'content' => 'updated'
        ];
        $re = $this->route('post_comment.edit', $param);
        test( is_error($re) == ERROR_NOT_YOUR_POST_DATA, "comment edit: " . get_error_string($re) );


        $param = [
            'idx' => $comment_idx,
            'session_id' => $this->getAdminSessionId(),
            'content' => 'updated'
        ];
        $re = $this->route('post_comment.edit', $param);
        test( is_success($re), "comment edit: " . get_error_string($re) );


        $param = [
            'idx' => $comment_idx,
            'session_id' => testUser()->getSessionId(),
            'content' => 'updated by test user'
        ];
        $re = $this->route('post_comment.edit', $param);
        test( is_success($re), "comment edit: " . get_error_string($re) );

        test( comment( $comment_idx )->content == 'updated by test user', 'content match');



        $param = [
            'idx' => $comment_idx
        ];
        $re = $this->route('post_comment.delete', $param);
        test( is_error($re) == ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS, "comment delete: " . get_error_string($re) );


        $param = [
            'idx' => $comment_idx,
            'session_id' => thruthesky()->getSessionId()
        ];
        $re = $this->route('post_comment.delete', $param);
        test( is_error($re) == ERROR_NOT_YOUR_POST_DATA, "comment delete: " . get_error_string($re) );


        $param = [
            'idx' => $comment_idx,
            'session_id' => testUser()->getSessionId()
        ];
        $re = $this->route('post_comment.delete', $param);
        test( is_success($re), "comment delete: " . get_error_string($re) );

        $re = $this->route('post_comment.delete', $param);
        test( is_error($re) == ERROR_ALREADY_DELETED, "comment already delete: " . get_error_string($re) );




        $comment_idx = $this->createComment([
            'parent_idx' => $parent_idx,
            'session_id' => thruthesky()->getSessionId(),
            'content' => "by thruthesky"
        ]);


        $param = [
            'idx' => $comment_idx,
            'session_id' => $this->getAdminSessionId()
        ];
        $re = $this->route('post_comment.delete', $param);
        test( is_success($re), "comment delete: " . get_error_string($re) );
        test( comment( $comment_idx )->deleted == 1, "comment deleted");


        $param = [
            'idx' => $parent_idx,
            'session_id' => $this->getAdminSessionId()
        ];
        $re = $this->route('post_data.delete', $param);
        test( is_success($re), "post delete: " . get_error_string($re) );
        test( post( $parent_idx )->deleted == 1, "post deleted");

    }

    public function tree_test() {


        // create a post
        $re = $this->route( 'post_data.create', [
            'post_config_id' => $this->test_config_id,
            'title' => 'hello',
            'password' => '1234a'] );

        $parent_idx = $re['data']['idx'];


        // create a comment.
        $param = [
            'parent_idx' => $parent_idx,
            'content' => '1'  ];

        // create 1st comment.
        $param['password'] = '1234a';

        $re = $this->route( 'post_comment.create', $param );
        test( is_success($re), " " . get_error_string($re) );
        $comments = comment()->getChildren( $parent_idx );
        test( count( $comments ) == 1, "create 1 comment");

        $comment_idx_1 = $re['data']['idx'];


        // create 2nd comment
        $param['content'] = '2';
        $re = $this->route( 'post_comment.create', $param );
        $comment_idx_2 = $re['data']['idx'];

        // create 3rd comment.
        $comments = comment()->getChildren( $parent_idx );
        test( count( $comments ) == 2, "created 2 comments");

        $comment_idx_3 = $this->createComment([
            'parent_idx'=> $parent_idx,
            'password' => 'abcd1',
            'content'=>'3'
        ]);
        test( $comment_idx_3, "comment created: $comment_idx_3" );



        // Creating a comment under a comment.
        $comment_idx_3_1 = $this->createComment([
            'parent_idx' => $comment_idx_3,
            'session_id' => testUser()->getSessionId(),
            'content' => "31 - $comment_idx_3"
        ]);



        // Create 4th comment.
        $comment_idx_4 = $this->createComment([
            'parent_idx' => $parent_idx,
            'password' => '1111a',
            'content' => "4 - $parent_idx"
        ]);

        // Create a child of 4th comment.
        $comment_idx_4_1 = $this->createComment([
            'parent_idx' => $comment_idx_4,
            'password' => '1111a',
            'content' => "41 - $comment_idx_4"
        ]);


        // Create a child of a child of comment 3
        $comment_idx_3_1_1 = $this->createComment([
            'parent_idx' => $comment_idx_3_1,
            'session_id' => testUser()->getSessionId(),
            'content' => "311 - $comment_idx_3_1"
        ]);

        // add another comment under 31. 312 will be created.
        $comment_idx_3_1_2 = $this->createComment([
            'parent_idx' => $comment_idx_3_1,
            'session_id' => testUser()->getSessionId(),
            'content' => "312 - $comment_idx_3_1"
        ]);
        // add another comment under 31. 313 will be created.
        $comment_idx_3_1_3 = $this->createComment([
            'parent_idx' => $comment_idx_3_1,
            'session_id' => testUser()->getSessionId(),
            'content' => "313 - $comment_idx_3_1"
        ]);


                // comment under 312. 3121 will be created.
                $comment_idx_3_1_2_1 = $this->createComment([
                    'parent_idx' => $comment_idx_3_1_2,
                    'session_id' => testUser()->getSessionId(),
                    'content' => "3121 - $comment_idx_3_1_2"
                ]);


        // comment under 3
                $comment_idx_3_2 = $this->createComment([
                    'parent_idx' => $comment_idx_3,
                    'session_id' => testUser()->getSessionId(),
                    'content' => "32 - $comment_idx_3"
                ]);



                // another comment under 3
                $comment_idx_3_3 = $this->createComment([
                    'parent_idx' => $comment_idx_3,
                    'session_id' => testUser()->getSessionId(),
                    'content' => "33 - $comment_idx_3"
                ]);

                // comment under 32

                $comment_idx_3_2_1 = $this->createComment([
                    'parent_idx' => $comment_idx_3_2,
                    'session_id' => testUser()->getSessionId(),
                    'content' => "321 - $comment_idx_3_2"
                ]);



                        // comment under 1
                        $comment_idx_1_1 = $this->createComment([
                            'parent_idx' => $comment_idx_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "11 - $comment_idx_1"
                        ]);


                        // comment under 2
                        $comment_idx_2_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "21 - $comment_idx_2"
                        ]);
                        $comment_idx_2_2 = $this->createComment([
                            'parent_idx' => $comment_idx_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "22 - $comment_idx_2"
                        ]);
                        $comment_idx_2_3 = $this->createComment([
                            'parent_idx' => $comment_idx_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "23 - $comment_idx_2"
                        ]);

                        // comment under 22
                        $comment_idx_2_2_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "221 - $comment_idx_2_2"
                        ]);
                        // comment under 221
                        $comment_idx_2_2_1_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "2211 - $comment_idx_2_2_1"
                        ]);
                        // comment under 221
                        $comment_idx_2_2_1_2 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "2212 - $comment_idx_2_2_1"
                        ]);
                        // comment under 221
                        $comment_idx_2_2_1_3 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "2213 - $comment_idx_2_2_1"
                        ]);
                        // comment under 2212
                        $comment_idx_2_2_1_2_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "22121 - $comment_idx_2_2_1_2"
                        ]);
                        // comment under 22121
                        $comment_idx_2_2_1_2_1_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "221211 - $comment_idx_2_2_1_2_1"
                        ]);


                        // comment under 2212
                        $comment_idx_2_2_1_2_2 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "22122 - $comment_idx_2_2_1_2"
                        ]);
                        // comment under 22122
                        $comment_idx_2_2_1_2_2_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "221221 - $comment_idx_2_2_1_2_2"
                        ]);
                        // comment under 221221
                        $comment_idx_2_2_1_2_2_1_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2_2_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "2212211 - $comment_idx_2_2_1_2_2_1"
                        ]);

                        // comment under 221221
                        $comment_idx_2_2_1_2_2_1_1_1 = $this->createComment([
                            'parent_idx' => $comment_idx_2_2_1_2_2_1_1,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "22122111 - $comment_idx_2_2_1_2_2_1_1"
                        ]);

                        // comment under 2
                        $comment_idx_2_4 = $this->createComment([
                            'parent_idx' => $comment_idx_2,
                            'session_id' => testUser()->getSessionId(),
                            'content' => "24 - $comment_idx_2"
                        ]);


        $comments_array = comment()->getRecords( "root_idx=$parent_idx ORDER BY order_no ASC ", "idx,root_idx,order_no,parent_idx,depth,content");


//        echo "<hr>";
//        foreach ( $comments_array as $c ) {
//            echo "<div>$c[idx], $c[root_idx], $c[parent_idx], $c[order_no], <b>$c[content]</b> / $c[depth]</div>";
//        }



        //echo "<hr> getChildren() recursively; ";
        $idxes = comment()->getChildren( $parent_idx );

        $comments_object = comment()->loads( $idxes, true );


//        foreach ( $comments_object as $c ) {
//            echo "<div>{$c->idx}, {$c->root_idx}, {$c->parent_idx}, {$c->order_no}, <b>{$c->content}</b> / {$c->depth}</div>";
//        }
//        echo '<hr>';


        //echo "<hr>getRecords order by content 3;";
        $comments_order_by_content = comment()->getRecords( "root_idx=$parent_idx ORDER BY content ASC ", "idx,root_idx,order_no,parent_idx,depth,content");

//        foreach ( $comments_order_by_content as $c ) {
//            echo "<div>$c[idx], $c[root_idx], $c[parent_idx], $c[order_no], <b>$c[content]</b> / $c[depth]</div>";
//        }



//
//        for ( $i=0; $i < count($comments_array); $i++ ) {
//            $a = $comments_array[$i];
//            $o = $comments_object[$i];
//            test( $a['order_no'] == $o->order_no, "
//                $a[idx], $a[parent_idx], <b>$a[order_no] = {$o->order_no}, $a[content]</b>, $a[depth]
//            ");
//        }

        // echo "<hr>";


        for ( $i=0; $i < count($comments_array); $i++ ) {
            $a = $comments_array[$i];
            $o = $comments_object[$i];
            $c = $comments_order_by_content[ $i ];
            test( ($a['order_no'] == $o->order_no) && ( $a['order_no'] == $c['order_no']), "
                ORDER LIST TEST : $a[idx], $a[parent_idx], <b>$a[order_no] = {$o->order_no}, <span style='color: darkred;'>$a[content]</span></b>, $a[depth]
            ");
        }
    }






}