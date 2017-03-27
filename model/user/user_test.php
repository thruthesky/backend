<?php

namespace model\user;
class User_Test extends \model\test\Test {


    public function single_test() {
        parent::$reload = 4;
        $this->run();
    }
    public function run() {


        $this->cache();
        $this->anonymous();
        $this->loadTest();
        $this->create();
        $this->register_with_login();
        $this->register();
        $this->edit();
        $this->meta();
        $this->login();
        $this->logout();
        $this->register_login_edit_resign();

        $this->search();

        $this->deleteTest();

    }


    public function cache() {


    }


    public function anonymous() {


        $an = currentUser();
        test( $an->exist(), "Anonymous user exists.");
        test( ! $an->logged(), "User : {$an->id}, Anonymous is a user who did not logged in with his password. So, he is NOT treated as logged in.");
        test( $an->id == ANONYMOUS_ID, "Before Login: User is anonymous.");
        $a = user( ANONYMOUS_ID );
        test( $a->id == ANONYMOUS_ID, "After loading anonymous user: Anonymous ID Exists");
    }

    public function loadTest() {
        $user = user( ADMIN_ID );
        test( $user->id == ADMIN_ID, "User load: admin");

        $user->reset( user( ANONYMOUS_ID ) );
        test( $user->id == ANONYMOUS_ID, "User load: anonymous");

        $user->reset( user( ADMIN_ID )->getRecord() );
        test( $user->id == ADMIN_ID, "User load: admin by record");

        $user->reset( user( ANONYMOUS_ID )->idx );
        test( $user->id == ANONYMOUS_ID, "User load: anonymous by idx");

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


    public function register_with_login() {
        $session_id = $this->createUser(['id'=>'id-' . date('his'), 'password'=>'pass']);

        $record['session_id'] = $session_id;
        $record['id'] = 'id2-' . date('his');
        $record['password'] = 'password';
        $re = $this->route("register", $record );
        //di($re);
        test( is_error($re) == ERROR_USER_LOGGED_IN, "User cannot register while logged in" . get_error_string($re));
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

//        di($re);

        test( is_error($re) == ERROR_MALFORMED_SESSION_ID, "session id should be malformed: $record[session_id] " . get_error_string($re));

        $record['session_id'] = $session_id;
        $re = $this->route('user.edit', $record);

        test( is_success($re) == ERROR_WRONG_SESSION_ID, "User edit: $id " . get_error_string($re));


        test( user( $id )->name == $new_name, "User name check: $new_name " . get_error_string($re));




        // admin edits user id.
        $admin_session_id = $this->getAdminSessionId();

        $record['session_id'] = $admin_session_id;
        $record['id'] = $id;            // user id to edit.
        $record['name'] = 'jaeho';
        $re = $this->route('user.edit', $record);
        test( is_success($re), "Admin: User edit: $id " . get_error_string($re));

        // $admin_session_id = $re['data']['session_id'];



        // get user data with malformed session-id
        $re = $this->route('user.data', [ 'session_id'=>'' ] );
        test( is_error($re) == ERROR_MALFORMED_SESSION_ID, "user data without session_id: " . get_error_string($re));


        // get user data with wrong session id
        $re = $this->route('user.data', [ 'session_id'=>'123-' . str_repeat( 'a', 32 ) ] );
        test( is_error($re) == ERROR_USER_NOT_FOUND, "user data with wrong session_id: " . get_error_string($re));

        // admin can get data of other user.
        $re = $this->route('user.data', [ 'session_id' => $admin_session_id, 'id' => $id ] );
        test( is_success($re), "admin get other user data: " . get_error_string($re));




        // user get user data
        $user = $this->route('user.data', [ 'session_id' => $this->getUserSessionId( $id ) ] );
        test( is_success($user), "user data: " . get_error_string($user));



        // prove

        test( $re['data']['user']['name'] == $user['data']['user']['name'], "name comparison: " );







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
        $re = $this->route('user.data', [ 'session_id' => $session_id ] );
        test( is_success($re), "Got user: $id " . get_error_string($re));

        test( $re['data']['user']['meta']['class_id'] == 'thruthesky', "Meta check. " . get_error_string($re)); // check


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
        $re = $this->route('user.data', [ 'session_id' => $new_session_id ] );
        test( $re['data']['user']['meta']['class_id'] == 'my-id', "Check updated meta data"); // check


    }


    public function login() {

        $this->createuser( [ 'id' => 'user1', 'password' => 'pass1'] );


        $re = $this->route("login", ['id'=>'user73nu', 'password'=>'pass1'] );
        test( is_error($re) == ERROR_USER_NOT_EXIST, "User NOT exist test : " . get_error_string($re) );


        $re = $this->route("login", ['id'=>'user1', 'password'=>'pass'] );
        test( is_error($re) == ERROR_WRONG_PASSWORD, "Wrong Password test : " . get_error_string($re) );


        $re = $this->route("login", ['id'=>'user1', 'password'=>'pass1'] );
        test( is_success($re), "User login: " . get_error_string($re) );

    }


    /**
     *
     * It simply erase user 'session_id' in database. So, the session_id issued previously is not valid.
     */
    public function logout() {

        // create a user
        $session_id = $this->createUser( ['id' => 'logout-test', 'password' => 'abc1234' ] );

        // check session id
        test( user( $session_id )->id == 'logout-test', "Logout user create: ");

        // user logout
        $re = $this->route('logout', [ 'session_id' => $session_id ] );
        test( is_success($re), "User Logout: $session_id");

        // check session id
        test( ! user( $session_id )->exist(), "Logout: after logout, session_id check: " . get_error_string($re) );

    }



    public function register_login_edit_resign() {
        $id = 'user-crud-test';
        $pw = 'pass';

        // create ( register )
        $session_id = $this->createUser( ['id'=>$id, 'password'=>$pw] );

        // login
        $re = $this->route('login', ['id'=>$id, 'password'=>$pw] );
        $login_session_id = $re['data']['session_id'];
        test( $session_id != $login_session_id, "User CRUD Login Test: $login_session_id" . get_error_string($re));


        // edit
        $re = $this->route('user.edit', [ 'session_id' => $login_session_id, 'name'=>'edited'] );

        test( is_success($re), "user.edit: " . get_error_string($re));
        $edit_session_id = $re['data']['session_id'];
        test( $login_session_id != $edit_session_id, "User edit test session_id: $edit_session_id" . get_error_string($re));



        // resign
        $re = $this->route('resign', [ 'session_id' => '731016' . $edit_session_id ] );
        test( is_error($re) == ERROR_WRONG_SESSION_ID, "User resign, wrong session_id test: " . get_error_string($re));

        // resign
        $re = $this->route('resign', [ 'session_id' => $edit_session_id ] );
        test( is_success($re), "User resign success: " . get_error_string($re));
    }


    public function search() {



        // create sample users
        //db()->beginTransaction();
        for( $i=0; $i < 22; $i++ ) {
            $record = [];
            $record[ 'id' ] = "searchuser$i";
            $record[ 'password' ] = "pass$i";
            $record[ 'name' ] = "name$i";
            $record[ 'email'] = "email$i";
            $record[ 'gender' ] = rand( 0, 1 ) ? 'M' : 'F';
            $re = $this->route( "register", $record );
        }
        //db()->commit();



        // test mismatch bind param. expect: error
        $re = $this->route( 'user.list', [
            'from' => 2,
            'limit' => 3,
            'where' => "name LIKE ? AND gender=?",
            'bind' => "%name%,M,aaa",
            'order' => 'idx ASC, name DESC'
        ]);

        test( is_error($re) == ERROR_SEARCH_MARK_LACK, "bind mismatch test: " . get_error_string($re) );


        // one bind is lacking. expect: query error.
        $re = $this->route( 'user.list', [
            'from' => 2,
            'limit' => 3,
            'where' => "name LIKE ? AND gender=?",
            'bind' => "%name%",
            'order' => 'idx ASC, name DESC'
        ]);

        test( is_error($re) == ERROR_SEARCH_BIND_LACK, "search query error test: " . get_error_string($re) );




        //
        $re = $this->route( 'user.list', [
            'from' => 2,
            'limit' => 3,
            'where' => "name LIKE ? AND gender=?",
            'bind' => "%name%,M",
            'order' => 'idx ASC, name DESC'
        ]);

        test( is_success($re), "user search name like name, male" . get_error_string($re));
        $count = count($re['data']['users']);
        test( $count == 3, "$count user searched");
        test( $re['data']['users'][0]['gender'] == 'M', 'male searched');



        // delete sample users.


        for( $i=0; $i < 22; $i++ ) {
            user( "searchuser$i" )->delete();
        }


    }

    public function deleteTest() {
        $id = "user-delete-test";
        $session_id = $this->createUser(['id' => $id, 'password' => 'test']);
        $re = $this->route('user.delete', [
            'session_id' => $session_id,
            'id' => $id
        ]);
        test( is_error($re) == ERROR_PERMISSION_ADMIN, "User cannot delete himself or other." . get_error_string($re));

        $admin_session_id = $this->getAdminSessionId();
        $re = $this->route("user.delete", [ 'session_id' => $admin_session_id, 'id' => 'abc197' . time() ] );
        test( is_error($re) == ERROR_USER_NOT_EXIST, "Wrong user id." . get_error_string($re));

        $admin_session_id = $this->getAdminSessionId();
        $re = $this->route("user.delete", [ 'session_id' => $admin_session_id, 'id' => $id ] );
        test( is_success($re) , "ADMIN - user delete." . get_error_string($re));

        test( ! user( $id )->exist(), "User not exists after delete" );



    }

}