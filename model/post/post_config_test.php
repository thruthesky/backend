<?php
namespace model\post;
use model\test\Test;

class Post_Config_Test extends Test {

    public function run() {
        $this->crud();
    }




     public function crud() {


        // Get admin session id.
        $re = $this->route("login", ['id'=>ADMIN_ID, 'password'=>ADMIN_ID]);
        test( is_success($re), "Admin login for config test: " . get_error_string($re));
        $admin_session_id = $re['data']['session_id'];


        // $this->createPostConfig('forum');

        //
        $id = "config-create-test-" . date('his');



        // Delete test config if exists.
        $re = $this->route( 'post_config.data', ['id' => $id] );
        // test( is_success($re), "post_config.data() id: $id" . get_error_string( $re ));

        if ( is_success($re) ) {
            $re = $this->route( 'post_config.delete', ['session_id' => $admin_session_id, 'id' => $id] );
            test( is_success($re), "Config deleted. " . get_error_string($re));
        }


        //
        $data['id'] = $id;
        $data['session_id'] = $this->createUser(['id'=>'id-' . date('His'), 'password' => 'password' ]);
        $data['name'] = "name" . $id;
        $data['description'] = "description-" . $id;


        // ERROR TEST : Did not yet logged in as admin.
        $re = $this->route("post_config.create", $data );
        test( is_error( $re ) == ERROR_PERMISSION_ADMIN, "Post Config Create: " . get_error_string( $re ) );


        // Config create with admin session id. Expect: success
        $data['session_id'] = $admin_session_id;
        $re = $this->route("post_config.create", $data );
        test( is_success( $re ), "Post config create with Admin session id: " . get_error_string( $re ) );


        // Edit post config. Expect: success.
        $data['name'] = "Forum Name";
        $re = $this->route( "post_config.edit", $data );
        test( is_success( $re ), "post config edit: " . get_error_string($re));



         // Delete. cleaning.
         $re = $this->route( 'post_config.data', ['id' => $id] );
         test( is_success($re), "post_config.data() id: $id" . get_error_string( $re ));
         if ( is_success($re) ) {
             $re = $this->route( 'post_config.delete', ['session_id' => $admin_session_id, 'id' => $id] );
             test( is_success($re), "post config deleted for cleaning: $id. " . get_error_string($re));
         }


     }


}