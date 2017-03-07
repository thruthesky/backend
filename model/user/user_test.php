<?php

namespace model\user;
class User_Test extends \model\test\Test {


    public function run() {


        $this->cache();


        $this->create();

        $this->register();
    }


    public function cache() {

        $admin = user('admin');
        $firstCount = $admin->getResetCount( 'admin' );

        $admin = user('admin');
        $secondCount = $admin->getResetCount( 'admin' );

        test( $firstCount == ($secondCount-1), "First cache count test");

        $admin = user('admin');
        $thirdCount = $admin->getResetCount( 'admin' );

        test( $firstCount == ($thirdCount-2), "Second cache count test");
    }


    public function create() {
        global $_cache_entity_record;

        $countAll = user()->countAll();

        $id = "user-create-test-1";
        $data['id'] = $id;
        $data['password'] = "pass-1";


        $session_id = user()->create( $data );

        if ( is_error( $session_id ) ) {
            if ( $session_id == ERROR_USER_EXIST ) {

                //di( $_cache_entity_record );

                user()->load( $id )->delete();

                //di( $_cache_entity_record );
                test( ! user()->load($id)->exist(), "User $id deleted." );
                $session_id = user()->create( $data );
            }
            else {
                test( $session_id, "User create failed. This should not happened : $session_id ");
            }
        }


        test( ! is_error( $session_id ), "User created: $id, $session_id" . get_error_string( $session_id ) );

        $user = user()->load( $id );
        test( $user->id == $id, "User id is: $id");

        user( $id )->delete();
        test( ! user()->load($id)->exist(), "User $id deleted." );
    }



    public function register() {
        $id = "user-register-test-1";
        $record = [];
        $record['id'] = $id;

        $re = $this->route("register", $record );
        di($re);

    }

}