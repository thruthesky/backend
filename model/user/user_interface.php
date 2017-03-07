<?php

namespace model\user;
class User_Interface extends User {



    public function register() {

        error( ERROR_UNKNOWN );
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
     * @return void
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

        if( empty( in('session_id')) ) return error( ERROR_SESSION_ID_EMPTY );
        $user = $this->load_by_session_id( in('session_id') );
        if ( empty($user) ) return error( ERROR_WRONG_SESSION_ID );


        if ( $this->isAdmin() ) { // if admin,
            if ( ! in('idx') ) return error( ERROR_IDX_EMPTY );
            $user = $this->load( in('idx') ); // load other user.
            if ( $user < 0 ) return error( $user );
            if ( empty($user) ) return error( ERROR_USER_NOT_FOUND );
        }

        unset( $user['password'], $user['session_id'], $user['stamp_registration'] );


        $_meta = meta()->gets( 'user', $user['idx'] );
        $metas = [];
        foreach( $_meta as $arr ) {
            $metas[ $arr['code'] ] = $arr['data'];
        }
        $user['meta'] = $metas;
        success( ['user'=>$user] );
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