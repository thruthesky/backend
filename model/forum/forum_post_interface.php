<?php

namespace model\forum;
class Forum_Post_Interface extends Forum_Post {

    /**
     * @param array $record
     * @return mixed
     */
    public function create( $record = [] ) {




        $config = config()->load( in( 'idx_config' ) );
        if( ! $config->exist() ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );
        if ( ! currentUser()->logged() ) return error( ERROR_USER_NOT_LOGIN );
        
        
        
        // if( ! is_numeric( in('idx_user') ) ) return error( ERROR_USER_IDX_NOT_NUMBER); // care it on somewhere else.
        

        $record = [];
        $record['idx_user'] = in('idx_user');
        $record['title'] = in('title');
        $record['content'] = in('content');
        $record['idx_config'] = in('idx_config');
        
        // if( empty( $record['title'] ) ) return error( ERROR_FORUM_DATA_TITLE_EMPTY ); // will allow empty title.
        if( strlen( $record['title'] ) > 254 ) return error( ERROR_TITLE_TOO_LONG );

        $idx_post = $this->create( $record );
        if ( is_error( $idx_post ) ) return error( $idx_post );
        return success( ['idx_post'=>$idx_post] );

    }


    public function edit() {

        // if( empty( in('session_id') ) ) return error( ERROR_SESSION_ID_EMPTY );
//        if( empty( in('idx_user') ) ) return error( ERROR_IDX_EMPTY );
        // if( !is_numeric(in('idx_user') ) ) return error( ERROR_USER_IDX_NOT_NUMBER );

        if( strlen( in('title') ) > 256 ) return error( ERROR_TITLE_TOO_LONG );

        //$user = user()->load_by_session_id( in('session_id') );
        //if( empty( $user ) ) return error( ERROR_USER_NOT_EXIST );

        if ( ! currentUser()->logged() ) return error( ERROR_WRONG_SESSION_ID ); // since the user sent a session_id but failed to login




        if ( ! post()->load( in('idx') )->exist() ) return error( ERROR_POST_NOT_EXIST );



        $record = [];
        $record['title'] = in('title');
        $record['content'] = in('content');

        $re = $this->update($record);

        if ( $re ) success( ['idx_post'=> $this->load( in('idx') )] );
        else error( ERROR_DATABASE_UPDATE_FAILED ); // should not happend.

    }


    public function delete( ) {


        if ( ! post()->load( in('idx') )->exist() ) return error( ERROR_POST_NOT_EXIST );

        if ( ! currentUser()->logged() ) return error( ERROR_USER_NOT_LOGIN ); // since the user sent a session_id but failed to login



        if ( post( in('idx') )->idx_user != currentUser()->idx ) return error( ERROR_USER_IDX_NOT_MATCHED );

        $re = post( in('idx') )->delete();

        if ( $re == OK ) success( ['idx_post' => in('idx') ]);
        else error( ERROR_DATABASE_INSERT_FAILED );

    }


    public function gets() {
        //if( empty( in( 'idx_config' ) ) ) return error( ERROR_FORUM_IDX_CONFIG_EMPTY );


        /*
        $record['idx_config'] = in('idx_config');
        $config = forum_config()->load( $record['idx_config']);
        if( empty($config) ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );
        */


        if ( ! config()->load( in('idx_config') )->exist() ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );
        $cond = "idx_config=" . in('idx_config');

        $posts = $this->loads($cond);

        success(['posts' => $posts ]);
    }


}