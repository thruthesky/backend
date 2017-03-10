<?php

namespace model\user;
class User_Interface extends User {



    public function register() {
        $record = get_route_variables();
        $re = user()->create( $record );
        is_success( $re ) ? success([ 'session_id' => $re ]) : error( $re );
    }


    public function edit() {

        $session_id = in('session_id');
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );


        $record = get_route_optional_variables();

        $re = $user->update( $record );

        if ( empty($re ) ) return error( ERROR_DATABASE_UPDATE_FAILED );

        $session_id = $user->getSessionId();
        return success( [ 'session_id' => $session_id ] );

    }


    /**
     *      HTTP INTERFACES
     *
     *
     */

    /**
     * Returns user record and its meta.
     *
     * @attention This is HTTP interface.
     * @attention if the login user is 'admin', then he can get other user's information.
     *
     *
     * @note if the session_id is 'admin', then it can have 'idx' to get other user's data.
     *
     * @return mixed
     *
     * @expected JSON return.
    Array
    (
    [code] => 0
    [data] => Array
    (
    [idx] => 14
    [id] => user-1486979723
    ...
    [meta] => Array( ... )
    )

    )
     *
     */
    public function get() {
        $session_id = in('session_id');
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );



        if ( $this->isAdmin() ) { // if admin,
            if ( ! in('idx') ) return error( ERROR_IDX_EMPTY );
            $user = $this->load( in('idx') ); // load other user.
            if ( $user < 0 ) return error( $user );
            if ( empty($user) ) return error( ERROR_USER_NOT_FOUND );
        }

        $record = $this->getRecord();
        unset( $record['password'], $record['session_id'] );




        $record['meta'] = meta()->get( $this->getTable(), $record['idx']);
        success( ['user'=>$record] );
    }



    public function login() {
        $user = user( in('id') );
        if ( ! $user->exist() ) return error(ERROR_USER_NOT_EXIST);
        if ( ! $this->checkPassword( in('password'), $user->password ) ) return error(ERROR_WRONG_PASSWORD);
        $user->updateLoginInformation();
        success( [ 'session_id' => $user->getSessionId() ] );
    }


    public function logout() {


        $session_id = in('session_id');
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );


        $user->update( [ 'session_id' => '' ]);
        success();

    }



    public function resign() {

        $session_id = in('session_id');
        if( empty( $session_id ) ) return error( ERROR_SESSION_ID_EMPTY );
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );

        $user->delete();
        $resigned = user()->load( $session_id  );
        if ( $resigned->exist() ) return error( ERROR_USER_RESIGN_FAILED );
        return success();

    }

    /**
     * Returns rows of user information.
     *
     * @attention this is HTTP interface.
     */
    public function search() {


        if ( empty( in('session_id') ) ) return error(ERROR_SESSION_ID_EMPTY );
        if ( ! $this->load_by_session_id( in('session_id') ) ) return error( ERROR_USER_NOT_FOUND );
        if ( ! $this->isAdmin() ) return error( ERROR_PERMISSION_ADMIN );


        $cond = in('cond');
        if ( empty($cond) ) $cond = 1;


        $page = page_no( in('page') );
        $limit = page_item_limit( in('limit') );
        $from = (( $page - 1 ) * $limit);
        $cond .= " LIMIT $from, $limit";


        $users = $this->loads( $cond );
        if ( $users < 0 ) return error( $users );


        $this->pres( $users );

        success( ['users' => $users ] );

    }


}