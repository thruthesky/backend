<?php
namespace model\post;
use model\test\Test;

class Post_Data_Test extends Test {

    public function run() {

        $this->create();
        $this->secret();

    }



    public function create() {

        // create by program.
        $idx = post()->set('title', "hi")->create();
        test( is_success( $idx ), "post create idx: $idx. " . get_error_string($idx));
        test( post( $idx )->title == 'hi', "post create title: hi" );

        $re = post( $idx )->delete();
        test ( is_success($re), "post delete test idx: $idx");
        test( post( $idx )->exist(), "post exits after delete: " . post($idx)->exist());
        test( post( $idx )->deleted(), "post deleted after delete: ");



        // prepare
        $session_id = $this->createUser( ['id' => 'user4', 'password' => 'pass4']);
        $this->createPostConfig( 'test2' );


        // create by router.



        // 2. post it with anonymous.


        // empty password error.
        $p = [ 'post_config_id'=>'test2', 'title'=>'hello there', 'content'=>'how are you?' ];
        $re = $this->route( 'post.create', $p);
        test( is_error($re) == ERROR_PASSWORD_EMPTY, get_error_string($re));

        // anonymous post with password.
        $p['password'] = '1234';
        $re = $this->route( 'post.create', $p);
        test( is_success($re), "Anonymous create with password : " . get_error_string($re));
        $post_idx = $re['data']['post_idx'];


        // anonymous get data
        $re = $this->route( 'post.data', ['idx' => $post_idx] );
        test( is_success($re), "Anonymous get data : " . get_error_string($re));
        test( $re['data']['post']['title'] == $p['title'], "Post title comparison: ");


        // anonymous edit post without password
        $re = $this->route('post.edit', ['idx' => $post_idx, 'title' => 'new title']);
        test( is_error($re) == ERROR_PASSWORD_EMPTY, "Anonymous edit empty password test : " . get_error_string($re));



        // anonymous edit post with wrong password
        $re = $this->route('post.edit', ['idx' => $post_idx, 'title' => 'new title', 'password' => 'abcd']);
        test( is_error($re) == ERROR_WRONG_PASSWORD, "Anonymous edit wrong password test : " . get_error_string($re));

        // anonymous edit post with right password
        $re = $this->route('post.edit', ['idx' => $post_idx, 'title' => 'new title', 'password' => $p['password']]);
        test( is_success($re), "Anonymous edit wrong password test : " . get_error_string($re));


        // title check
        $re = $this->route( 'post.data', ['idx' => $post_idx] );
        test( is_success($re), "Anonymous get data : " . get_error_string($re));
        test( $re['data']['post']['title'] == 'new title', "Post title comparison after edit: ");


        // delete

        // delete post that does not exist.

        $re = $this->route( 'post.delete', ['idx' => time() ] );
        test( is_error( $re ) == ERROR_POST_NOT_EXIST, "post not exist: " . get_error_string($re));


        // delete without session_id, password.
        $re = $this->route( 'post.delete', ['idx' => $post_idx] );
        test( is_error( $re ) == ERROR_PASSWORD_EMPTY, "Wrong password test" . get_error_string($re));

        // test with a user who does not own that post. expect: error.
        $re = $this->route( 'post.delete', [ 'session_id' => $session_id, 'idx' => $post_idx] );
        test( is_error( $re ) == ERROR_NOT_YOUR_POST_DATA, "Delete with wrong user(session_id)" . get_error_string($re));


        // delete with right password. expect success.
        $re = $this->route( 'post.delete', [ 'password' => $p['password'], 'idx' => $post_idx] );
        test( is_success( $re ), "Anonymous delete with password: " . get_error_string($re));



        // 3. post with user.
        $p = [ 'session_id' => $session_id, 'post_config_id'=>'test2', 'title'=>'hello there', 'content'=>'how are you?' ];
        $re = $this->route( 'post.create', $p);
        test( is_success($re), "A user creates a post: " . get_error_string($re));

        $post_idx = $re['data']['post_idx'];



        // edit without idx
        $re = $this->route('post.edit', ['title' => 'title edited'] );
        test( is_error($re), "edit without idx: " . get_error_string($re));



        // edit without session_id( Anonymous ). if no session id, he is anonymous. need a password.
        $re = $this->route('post.edit', [ 'idx' => $post_idx, 'title' => 'title edited'] );
        test( is_error($re) == ERROR_PASSWORD_EMPTY, "edit without session_id: " . get_error_string($re));



        // edit with wrong idx.
        $re = $this->route('post.edit', [ 'idx' => time(), 'session_id' => $session_id, 'title' => 'title edited'] );
        test( is_error($re) == ERROR_POST_NOT_EXIST, "edit with wrong idx: " . get_error_string($re));



        // edit with wrong session_id
        $re = $this->route('post.edit', [ 'idx' => $post_idx, 'session_id' => time(), 'title' => 'title edited'] );
        test( is_error($re) == ERROR_NOT_YOUR_POST_DATA, "edit wrong user_idx: " . get_error_string($re));


        // edit without session_id( Anonymously ) with wrong password. expect: Not Your Post
        $re = $this->route('post.edit', [ 'idx' => $post_idx, 'password' => time(), 'title' => 'title edited'] );
        test( is_error($re) == ERROR_NOT_YOUR_POST_DATA, "edit with wrong password: " . get_error_string($re));

        // edit it. expect: success.
        $re = $this->route( 'post.edit', ['idx' => $post_idx, 'session_id'=>$session_id, 'title' => 'title 3'] );
        test( is_success( $re ), "post edit: ");

        // check if post edited. expect: susccess.
        $re = $this->route( 'post.data', ['idx' => $post_idx] );
        test( is_success($re), "post.data after edit: " . get_error_string($re) );
        test( $re['data']['post']['title'] == 'title 3', "title comparision after post edit: ");





        // delete wrong post_idx
        $re = $this->route( 'post.delete', ['idx' => time()]);
        test( is_error($re) == ERROR_POST_NOT_EXIST, "delete with wrong post idx: " . get_error_string($re));

        // delete with Anonymous. without session_id and without password. expect: password empty. Anonymous must send password.
        $re = $this->route( 'post.delete', ['idx' => $post_idx]);
        test( is_error($re) == ERROR_PASSWORD_EMPTY, "delete without session_id: " . get_error_string($re));


        // delete with wrong password
        $re = $this->route( 'post.delete', ['idx' => $post_idx, 'password' => time() ]);
        test( is_error($re) == ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS, "delete with wrong password: " . get_error_string($re));


        // delete with wrong session_id. expect: error.
        $re = $this->route( 'post.delete', ['idx' => $post_idx, 'session_id' => time() ]);
        test( is_error($re) == ERROR_NOT_YOUR_POST_DATA, "delete with wrong session_id: " . get_error_string($re));



        // delete it. expect: success
        $re = $this->route( 'post.delete', ['idx' => $post_idx, 'session_id' => $session_id ]);
        test( is_success($re), "delete with session_id: " . get_error_string($re));


        // check it.
        $re = $this->route( 'post.data', ['idx' => $post_idx ] );
        test( is_success( $re ), "get deleted post" . get_error_string($re) );
        test( $re['data']['post']['deleted'] == 1, "post has been deleted");


        // 4. admin edit & delete a post owned by other.


        $p = [ 'session_id' => $session_id, 'post_config_id'=>'test2', 'title'=>'admin test', 'content'=>'content 4' ];
        $re = $this->route( 'post.create', $p);
        test( is_success($re), "Admin test: A user creates a post: " . get_error_string($re));
        $post_idx = $re['data']['post_idx'];

        $admin_session_id = $this->getAdminSessionId();

        $re = $this->route( 'post.edit', ['session_id' => $admin_session_id, 'idx' => $post_idx, 'title'=>'edited 4'] );
        test( is_success($re), "admin post edit: " . get_error_string($re));

        // prove edit.
        $re = $this->route( 'post.data' , ['idx'=>$post_idx] );
        test( $re['data']['post']['title'] == 'edited 4', "admin post edit prove: ");

        // delete
        $re = $this->route( 'post.delete' , ['idx'=>$post_idx, 'session_id' => $admin_session_id] );
        test( is_success( $re ), "admin post delete: " . get_error_string($re));


        // prove delete.
        $re = $this->route( 'post.data', ['idx' => $post_idx ] );
        test( $re['data']['post']['deleted'] == 1, "Admin: post has been deleted");




        $this->deletePostConfig( 'test2' );
        $this->deleteUser( $session_id );

    }
    public function secret() {

    }
}