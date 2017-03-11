<?php

namespace model\post;
class Post_Data_Interface extends Post_Data {

    /**
     * @param array $record
     * @return mixed
     */
    public function create( $record = [] ) {


        $config = config()->load( in( 'post_config_id' ) );
        if( ! $config->exist() ) return error( ERROR_POST_CONFIG_NOT_EXIST );


        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return error( ERROR_PASSWORD_EMPTY, "Anonymous must input a password to create a post.");

        $record = get_route_optional_variables();
        $record['user_idx'] = currentUser()->idx;
        $record['post_config_idx'] = $config->idx;


        // sanitizing.
        $record['ip'] = $_SERVER['REMOTE_ADDR'];
        $record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        // password
        if ( in( 'password' ) ) $record['password'] = $this->encryptPassword( in('password') );

        /*
        debug_log('_request'); debug_log($_REQUEST);
        debug_log('record:'); debug_log( $record);
        */

        if( strlen( $record['title'] ) > 254 ) return error( ERROR_TITLE_TOO_LONG );

        $post_idx = parent::create( $record );
        if ( is_error( $post_idx ) ) return error( ERROR_DATABASE_INSERT_FAILED );
        return success( ['post_idx'=>$post_idx] );

    }


    /**
     *
     * @note ip, user_agent does not changes when edit.
     *
     * @return mixed
     */
    public function edit() {

        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return error( ERROR_PASSWORD_EMPTY, "Anonymous must input a password to edit a post.");
        if ( ! $this->load( in('idx') )->exist() ) return error( ERROR_POST_NOT_EXIST );


        if ( $re = $this->editPermission() ) return error( $re );



        if( strlen( in('title') ) > 254 ) return error( ERROR_TITLE_TOO_LONG );

        $record = get_route_optional_variables();

        unset( $record['password'] ); // no need to set password again.

        $re = $this->update($record);

        if ( is_success( $re ) ) success( ['post_idx'=> $this->idx] );
        else error( ERROR_DATABASE_UPDATE_FAILED ); // should not happened.

    }


    /**
     *
     * This does not actually delete post. it just mark as delete. it does not delete meta data of the post also.
     *
     * @return mixed
     */
    public function delete( ) {

        $this->load( in('idx') );

        if ( ! $this->exist() ) return error( ERROR_POST_NOT_EXIST );


        if ( $re = $this->deletePermission() ) return error( $re );

        $re = parent::delete();


        // $re = post( in('idx') )->delete(); // it does not delete.

        if ( is_success($re) ) success( ['post_idx' => in('idx') ]);
        else error( ERROR_DATABASE_DELETE_FAILED );

    }

    public function data() {

        $post = $this->load( in('idx') );
        if ( ! $post->exist() ) return error( ERROR_POST_DATA_NOT_EXIST );
        success( ['post'=> $post->pre() ] );

    }

    /*
    public function gets() {

        if ( ! config()->load( in('post_config_idx') )->exist() ) return error( ERROR_POST_CONFIG_NOT_EXIST );
        $cond = "post_config_idx=" . in('post_config_idx');

        $posts = $this->loads($cond);

        success(['posts' => $posts ]);
    }
    */


}