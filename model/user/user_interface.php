<?php

namespace model\user;
class User_Interface extends User {



    public function register() {
        $record =  route()->get_route_variables();
        $re = user()->create( $record ); // session id.


        if ( is_success($re ) ) {



            $user = user( $re );
            $this->reset( $user );
            //debug_log('this');
            //$user->debug_log();

            setCurrentUser( $user );
            //debug_log('current');
            //global $_currentUser;
            //debug_log($_currentUser->getRecord());

            //debug_log(currentUser()->getRecord());




            $re_upload = $this->hookUpload( $this, BACKEND_PRIMARY_PHOTO ); if ( is_error( $re_upload ) ) return error( $re_upload );


            $res = [
                'session_id' => $re,
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email
            ];
            // debug_log("res");
            // debug_log($res);
            success( $res );
        }
        else {
            error( $re );
        }
//        is_success( $re ) ? success([ 'session_id' => $re ]) : error( $re );
    }


    public function edit() {


        // check-ups
        if ( currentUser()->isAnonymous() ) return error( ERROR_ANONYMOUS_CAN_NOT_EDIT_PROFILE );

        //di($_REQUEST);



        $record = route()->get_route_optional_variables();




        if ( currentUser()->isAdmin() ) {
            $user = user( in('id') );
        }
        else {
            $user = user( in('session_id') );
        }

        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );
        $re = $user->update( $record );


        /**
         * For admin, return data is admin's data after changing user's information.
         */
        if ( is_success( $re ) ) {
            $re_upload = $this->hookUpload( $user, BACKEND_PRIMARY_PHOTO );
            if ( is_error( $re_upload ) ) return error( $re_upload );
            $res = [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email
            ] ;
            if ( ! currentUser()->isAdmin() ) $res[ 'session_id' ] = $user->getSessionId();
            success( $res );
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

        success( [
            'session_id' => $user->getSessionId(),
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->mail
        ] );
    }


    public function logout() {


        $session_id = in('session_id');
        $user = $this->load( $session_id );
        if ( ! $user->exist() ) return error( ERROR_USER_NOT_FOUND );

        $user->update( [ 'session_id' => '' ]);
        success();

    }



    public function resign() {

        $session_id = in('session_id');
        $user = user()->load( $session_id );
        $user->delete();
        $resigned = user()->load( $session_id  );
                                                                    // debug_log( $resigned ); exit;
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
            'where' => in('where'),
            'bind' => in('bind'),
            'order' => in('order')
        ];
        $users = parent::search( $option );
        if ( is_error( $users ) ) return error( $users );
        success( [
            'total' => parent::countSearch( $option ),
            'users' => user()->pres( $users )
        ] );

    }



    /**
     *
     * Only admin can delete a user.
     *
     * Users can resign but cannot delete.
     *
     *
     * @return mixed
     */
    public function delete() {
                                                        //        debug_log("User_Interface::delete()");

        $user = user( in('id') );
                                                        //        debug_log( $user );
        $re = $user->delete();
        if ( is_success($re) ) success( ['id' => in('id') ]);
        else error( ERROR_DATABASE_DELETE_FAILED );
    }

}