<?php

namespace model\user;
class User_Test extends \model\test\Test {


    public function run() {


        $this->cache();
        $this->create();
        $this->register();
        $this->edit();
        $this->meta();

        $this->login();

        $this->register_login_edit_resign();


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


        $re = $this->route("register", $record );
        test( is_error( $re ), "User reigster: " . get_error_string( $re ) );

        $record['id'] = $id;
        $re = $this->route("register", $record );
        test( is_error( $re ), "User reigster: " . get_error_string( $re ) );

        $record['password'] = $id;


        $wrong_var = "WRONG-VARIABLE-NAME";
        $record[$wrong_var] = $id;
        $re = $this->route("register", $record );
        test( is_error( $re ), "User reigster: $wrong_var error. " . get_error_string( $re ) );

        unset( $record[$wrong_var] );


        $record['name'] = "User Name";
        $re = $this->route("register", $record );
        if ( is_error( $re ) == ERROR_USER_EXIST ) {
            user()->load( $id )->delete();
            test( ! user()->load( $id )->exist(), "User deleted: $id" . get_error_string($re) );
            $re = $this->route("register", $record );
        }
        test( is_success( $re ), "User reigster OK. id: $id " . get_error_string( $re ) );

        test( user()->load( $id )->id == $id, "User ID comparison: $id " . get_error_string($re ) );

        user()->load( $id )->delete();
        test( ! user()->load( $id )->exist(), "User deleted: $id" . get_error_string($re) );

    }


    public function edit() {

        $id = "user-edit-test-1";
        $name = "name-$id";
        $new_name = "New name";

        if ( user()->load( $id )->exist() ) user()->load( $id )->delete();

        $record = [];
        $record[ 'id' ] = $id;
        $record[ 'password' ] = $id;
        $record[ 'name' ] = $name;
        $re = $this->route( "register", $record );
        test( is_success($re), "User register: $id " . get_error_string($re));

        //
        $session_id = $re['data']['session_id'];

        test( user( $id )->name == $name, "User name check: $name " . get_error_string( $re ) );


        //
        unset( $record['id'], $record['password'] );
        $record[ 'session_id' ] = "WRONG SESSION ID" . $session_id;
        $record[ 'name' ] = $new_name;
        $record[ 'address' ] = "My Address Is ...";

        $re = $this->route('user.edit', $record);
        test( is_error($re) == ERROR_MALFORMED_SESSION_ID, "User edit: $id " . get_error_string($re));

        $record['session_id'] = $session_id;
        $re = $this->route('user.edit', $record);
        test( is_success($re) == ERROR_WRONG_SESSION_ID, "User edit: $id " . get_error_string($re));


        test( user( $id )->name == $new_name, "User name check: $new_name " . get_error_string($re));




    }


    public function meta() {


        $id = "user-meta-test-1";
        user( $id )->delete();

        $record = [
            'id' => $id,
            'password' => $id,
            'name' => 'User Test Name',
            'meta' => [
                'a' => 'b',
                'class_id' => 'thruthesky'
            ]
        ];

        $re = $this->route('register', $record);
        test( is_success($re), "User registration for meta: $id " . get_error_string( $re ));

        $session_id = $re['data']['session_id'];


        // get user data.
        $re = $this->route('user.get', [ 'session_id' => $session_id ] );
        test( is_success($re), "Got user: $id " . get_error_string($re));
        test( $re['data']['user']['meta']['class_id'] == 'thruthesky', "Meta check"); // check


        // meta update
        $record = [
            'session_id' => $session_id,
            'meta' => [
                'class_id' => 'my-id'
            ]
        ];
        $re = $this->route('user.edit', $record);
        test( is_success($re), "user edit for meta update" . get_error_string($re));

        $new_session_id = $re['data']['session_id'];

        // get updated meta dta.
        $re = $this->route('user.get', [ 'session_id' => $new_session_id ] );
        test( $re['data']['user']['meta']['class_id'] == 'my-id', "Check updated meta data"); // check


    }


    public function login() {

        $this->createuser( [ 'id' => 'user1', 'password' => 'pass1'] );


        $re = $this->route("login", ['id'=>'user', 'password'=>'pass1'] );
        test( is_error($re) == ERROR_USER_NOT_EXIST, "User NOT exist test : " . get_error_string($re) );


        $re = $this->route("login", ['id'=>'user1', 'password'=>'pass'] );
        test( is_error($re) == ERROR_WRONG_PASSWORD, "Wrong Password test : " . get_error_string($re) );


        $re = $this->route("login", ['id'=>'user1', 'password'=>'pass1'] );
        test( is_success($re), "User login: " . get_error_string($re) );


    }

    public function register_login_edit_resign() {
        $id = 'user-crud-test';
        $pw = 'pass';

        $session_id = $this->createUser( ['id'=>$id, 'password'=>$pw] );

        $re = $this->route('login', ['id'=>$id, 'password'=>$pw] );
        $login_session_id = $re['data']['session_id'];
        test( $session_id != $login_session_id, "User CRUD Login Test: " . get_error_string($re));


        $re = $this->route('user.edit', [ 'session_id' => $login_session_id, 'name'=>'edited'] );
        $edit_session_id = $re['data']['session_id'];
        test( $login_session_id != $edit_session_id, "User CRUD Update Test: " . get_error_string($re));

        $re = $this->route('resign', [ 'session_id' => $edit_session_id ] );
        test( is_success($re), "User resign success: ");
    }
}