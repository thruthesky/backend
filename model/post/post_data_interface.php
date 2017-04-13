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
        post( $post_idx )->updateFirstImage();
        $post = post( $post_idx );
        return success( $post->pre( [ 'extra' => [ 'user' => true, 'file' => true, 'comment' => true, 'meta' => true ] ] ) );

    }


    /**
     *
     * @note ip, user_agent does not changes when edit.
     *
     * @param Post $post
     * @param Post_Config $config
     * @return mixed
     */
    public function edit( $post, $config ) {


        $record = route()->get_route_optional_variables();

        unset( $record['password'] ); // no need to set password again.

        $re = $post->update($record);

        if ( is_success( $re ) ) {
            $re_upload = $post->hookUpload( post( in('idx') ) ); if ( is_error( $re_upload ) ) return error( $re_upload );
            post( $post->idx )->updateFirstImage();
            $post = post( $post->idx );
            return success( $post->pre( [ 'extra' => [ 'user' => true, 'file' => true, 'comment' => true, 'meta' => true ] ] ) );

            //success( ['idx'=> $this->idx] );
        }
        else error( ERROR_DATABASE_UPDATE_FAILED ); // should not happened.

    }


    /**
     *
     * This does not actually delete post. it just mark as delete. it does not delete meta data of the post also.
     *
     * @param Post_Data $post
     * @param Post_Config $config
     * @return mixed
     */
    public function delete( $post=null, $config=null ) {

        $re = $post->delete();

        if ( is_success($re) ) success( ['idx' => in('idx') ]);
        else error( $re );

    }

    public function data() {

        $post = $this->load( in('idx') );
        if ( is_error( $post ) ) return error( $post );
        if ( ! $post->exist() ) return error( ERROR_POST_DATA_NOT_EXIST );
        success( ['post'=> $post->pre( $_REQUEST ) ] );

    }


    /**
     * @param null $_
     * @return mixed
     */
    public function search( $_=null ) {



        $option = $this->processSearchVariables();
                            if ( is_error( $option ) ) return error( $option );


        /**
         * post search by 'id'
         */

        $extra = in('extra');
    	$post_config_id = '';
        if ( isset($extra['post_config_id']) ) {
		    $post_config_id = $extra['post_config_id'];
            $config = config( $extra['post_config_id'] );
            if ( $config->exist() ) {
                debug_log($option);
                $option['where'] = "post_config_idx = ? " . ( $option['where']  ?  "AND ($option[where])" : '' );
                $option['bind'] = $config->idx . ( isset($option['bind']) ? ",$option[bind]" : '' );
            }
            else {
                return error( ERROR_FORUM_NOT_EXIST );
            }
        }



        $re = parent::search( $option ); if ( is_error( $re ) ) return error( $re );
        $posts = $re[0];

        $pre_option = [];
        if ( isset($_REQUEST['extra']) ) $pre_option['extra'] = $_REQUEST['extra'];

        /// fix: returns-post_config-of-requested-post-config-id-if-no-posts.
        $configs = post()->getConfigs( $posts );
        if ( empty( $configs ) && $post_config_id ) $configs = [ config( $post_config_id )->getRecord() ];

        success( [
            'total' => parent::countSearch( $option ),
            'configs' => $configs,
            'posts' => post()->pres( $posts, $pre_option ),
            'page' => $option['page'],
            'limit' => $option['org_limit'],
    		'post_config_id' => $post_config_id
        ] );

    }





}
