<?php

namespace model\user;
class User_Interface extends User {



    public function register() {
        $record = get_route_variables();
        $re = user()->create( $record );
        is_success( $re ) ? success([ 'session_id' => $re ]) : error( $re );
    }


    public function edit() {

        // check ups
        $session_id = in('session_id');
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );




        if ( currentUser()->isAnonymous() ) return error( ERROR_ANONYMOUS_CAN_NOT_EDIT_PROFILE );

        if ( currentUser()->isAdmin() ) {

            $user = user( in('id') );           // switch $user to the 'user' to be edited.
            if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );
            $this->forceLogin( in('id') );
        }

        $record = get_route_optional_variables();
        $re = $this->update( $record );


        if ( is_success( $re ) ) {
            $session_id = $this->getSessionId();
            success( [ 'session_id' => $session_id ] );
        }
        else error( ERROR_DATABASE_UPDATE_FAILED );

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
    public function data() {
        $session_id = in('session_id');
        if ( ! $this->isSessionId( $session_id ) ) return error( ERROR_MALFORMED_SESSION_ID );

        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND, "user-not-found-by-session-id: $session_id" );

        if ( $this->isAdmin() && in('id') ) { // if admin,
            $user = $this->load( in('id') ); // load other user.
            if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );
            $this->forceLogin( in('id') );
        }


        success( [ 'user' => $this->pre() ] );
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
     * @param null $_
     * @return int|mixed
     */
    public function search( $_=null ) {

        $option = [
            'from' => in('from'),
            'limit' => in('limit'),
            'statement' => in('where'),
            'bind' => in('bind'),
            'order' => in('order')
        ];
        $users = parent::search( $option );
        if ( is_error( $users ) ) return error( $users );
        success( ['users' => user()->pres( $users )] );

    }


}