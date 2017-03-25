<?php

namespace model\post;
class Post_Data_Interface extends Post_Data {

    /**
     * @param array $record
     * @return mixed
     */
    public function create( $record = [] ) {



        // @todo move it to validator
        $config = config()->load( in( 'post_config_id' ) );
        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return error( ERROR_PASSWORD_EMPTY, "Anonymous must input a password to create a post.");

        $record = route()->get_route_optional_variables();
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

        // @todo move it to validator
        if( isset($record['title']) && strlen( $record['title'] ) > 254 ) return error( ERROR_TITLE_TOO_LONG );

        $post_idx = parent::create( $record );
        if ( is_error( $post_idx ) ) return error( $post_idx );



        $re_upload = $this->hookUpload( post( $post_idx ) ); if ( is_error( $re_upload ) ) return error( $re_upload );


        return success( ['idx'=>$post_idx] );

    }


    /**
     *
     * @note ip, user_agent does not changes when edit.
     *
     * @return mixed
     */
    public function edit() {

        // @todo move checkups and permission check to 'validator'
        if ( empty( in('idx') ) ) return error( ERROR_IDX_EMPTY );
        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return error( ERROR_PASSWORD_EMPTY, "Anonymous must input a password to edit a post.");
        $re = $this->load( in('idx') );
        if ( is_error($re) ) return error( $re );
        if ( ! $this->load( in('idx') )->exist() ) return error( ERROR_POST_NOT_EXIST );


        if ( $re = $this->editPermission() ) return error( $re );

        if( strlen( in('title') ) > 254 ) return error( ERROR_TITLE_TOO_LONG );

        $record = route()->get_route_optional_variables();

        unset( $record['password'] ); // no need to set password again.

        $re = $this->update($record);

        if ( is_success( $re ) ) {
            $re_upload = $this->hookUpload( post( in('idx') ) ); if ( is_error( $re_upload ) ) return error( $re_upload );
            success( ['idx'=> $this->idx] );
        }
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

        if ( is_success($re) ) success( ['idx' => in('idx') ]);
        else error( ERROR_DATABASE_DELETE_FAILED );

    }

    public function data() {

        $post = $this->load( in('idx') );
        if ( ! $post->exist() ) return error( ERROR_POST_DATA_NOT_EXIST );
        success( ['post'=> $post->pre() ] );

    }


    /**
     * @param null $_
     * @return mixed
     */
    public function search( $_=null ) {


        $extra = in('extra');

        $option = [
            'select' => in('select'),
            'from' => in('from'),
            'limit' => in('limit'),
            'where' => in('where'),
            'bind' => in('bind'),
            'order' => in('order')
        ];
        if ( isset($extra['post_config_id']) ) {
            $config = config( $extra['post_config_id'] );
            if ( $config->exist() ) {
                debug_log($option);
                $option['where'] = "post_config_idx = ? " . ( $option['where']  ?  "AND ($option[where])" : '' );
                $option['bind'] = $config->idx . ( $option['bind'] ? ",$option[bind]" : '' );
            }
            else {
                return error( ERROR_FORUM_NOT_EXIST );
            }
        }
        $posts = parent::search( $option );


        if ( is_error( $posts ) ) return error( $posts );


        $pre_option = [];
        if ( isset($_REQUEST['extra']) ) $pre_option['extra'] = $_REQUEST['extra'];

        success( [
            'total' => parent::countSearch( $option ),
            'configs' => post()->getConfigs( $posts ),
            'posts' => post()->pres( $posts, $pre_option )
        ] );

    }



}