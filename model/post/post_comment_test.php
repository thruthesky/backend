<?php
namespace model\post;
use model\test\Test;

class Post_Comment_Test extends Test {

    private  $test_config_id;

    public function single_test() {
        parent::$reload = 2;
        $this->run();
    }
    public function run() {

        $this->test_config_id = 'forum-comment-test';
//        $re = $this->route('post_config.create', [ 'id' => $this->test_config_id, 'session_id' => $this->getAdminSessionId() ]);
//        if ( is_error($re) ) test( $re, '' . get_error_string($re) );

        $this->createPostConfig( $this->test_config_id );

        $this->crud();

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
            'content' => 'comment content'  ];

        // empty password error.
        $re = $this->route( 'post_comment.create', $param );
        test( is_error($re) == ERROR_PASSWORD_EMPTY, " " . get_error_string($re) );


        //
        $param['password'] = '1234a';

        $re = $this->route( 'post_comment.create', $param );
        test( is_success($re), " " . get_error_string($re) );
        $comments = comment()->getChildren( $parent_idx );
        test( count( $comments ) == 1, "comment 1 created");



        $re = $this->route( 'post_comment.create', $param );
        $comments = comment()->getChildren( $parent_idx );
        test( count( $comments ) == 2, "comment 2 created");


    }




}