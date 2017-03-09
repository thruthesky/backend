<?php
namespace model\forum;
class Forum_Config_Interface extends Forum_Config {


    /**
     *
     *
     * HTTP interface
     *
     * @param array $record
     * @return mixed
     */
    public function create( $record = [] ) {

        if ( empty(in('id') ) ) return error( ERROR_FORUM_ID_EMPTY ); // @fixed. put right error message.

        $data = [];
        $data['id'] = in('id');
        $data['name'] = in('name');
        $data['description'] = in('description');
        if( strlen( in('id') ) > 64 ) return error( ERROR_FORUM_CONFIG_ID_IS_TOO_LONG );
        if( strlen( in('name') ) >128) return error( ERROR_FORUM_CONFIG_NAME_IS_TOO_LONG );


        if ( ! currentUser()->isAdmin() ) return error( ERROR_PERMISSION_ADMIN );


        $config = $this->load( in('id') );

        if ( $config->exist() ) return error( ERROR_FORUM_CONFIG_EXIST ); //

        $forum_idx = parent::create( $data );
        if ( is_error( $forum_idx ) ) error( $forum_idx );
        else success( ['idx'=>$forum_idx] );
    }





    /**
     * @return mixed
     */
    public function edit() {

        // check-up
        $config = $this->load(in('idx'));
        if( ! $config->exist() ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );

        $record = [];
        // $record['idx'] = in('idx');
        // $data['id'] = in('id');          // you cannot edit id.
        $record['name'] = in('name');
        $record['description'] = in('description');

        $re = $config->update( $record );
        if ( $re == FALSE ) return error( ERROR_FORUM_CONFIG_EDIT_FAILED ); // strange error. this error should not happened here.
        success();
        return OK;
    }



    /**
     * HTTP interface
     * @return mixed
     */
    public function delete() {

        // check-up
        $config = $this->load(in('idx'));
        if( ! $config->exist() ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );


        $re = parent::delete();
        if ( $re == OK ) return success();
        else return error( $re );

    }


    public function data() {

        // check-up
        $config = $this->load(in('idx'));
        if( ! $config->exist() ) return error( ERROR_FORUM_CONFIG_NOT_EXIST );

        success( [ 'config' => $config->getRecord() ] );

    }


}