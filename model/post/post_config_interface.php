<?php
namespace model\post;
class Post_Config_Interface extends Post_Config {


    /**
     *
     *
     * HTTP interface
     *
     * @param array $record
     * @return mixed
     *
     *
     *
     *
     */
    public function create( $record = [] ) {
        if ( ! currentUser()->isAdmin() ) return error( ERROR_PERMISSION_ADMIN );
        if ( $this->load( in('id') )->exist() ) return error( ERROR_POST_CONFIG_EXIST, "post config - " . in('id') . " - already exists" );

        $record = route()->get_route_optional_variables();
        $record['id'] = in('id');
        $forum_idx = parent::create( $record );
        if ( is_error( $forum_idx ) ) error( $forum_idx );
        else success( ['idx'=>$forum_idx] );
    }






    /**
     * @return mixed
     */
    public function edit() {


        if ( ! currentUser()->isAdmin() ) return error( ERROR_PERMISSION_ADMIN );


        // check-up
        $config = $this->load(in('id'));
        if( ! $config->exist() ) return error( ERROR_POST_CONFIG_NOT_EXIST, "post config - " . in('id') . ' - already does not exist' );


        $record = route()->get_route_optional_variables();
        $record['id'] = in('id');
        $re = $config->update( $record );
        if ( $re == FALSE ) return error( ERROR_POST_CONFIG_EDIT_FAILED ); // strange error. this error should not happened here.
        success([]);
    }


    /**
     * HTTP interface
     * @param Post_Config $config
     * @return mixed
     */
    public function delete( $config=null ) {


        if ( $config->deleted() ) return error( ERROR_ALREADY_DELETED );



        $re = $config->update([
            'deleted' => '1',
        ]);

        if ( is_error($re) ) {
            if ( $re === false ) error( ERROR_DATABASE_UPDATE_FAILED );
            else error( $re );
        }
        else success( [ 'id' => $config->id ] );

    }


    /**
     * @param Post_Config $config
     * @return mixed
     */
    public function data( $config ) {
        success( [ 'config' => $config->getRecord() ] );
    }



    /**
     * @param null $_
     * @return mixed
     */
    public function search( $_=null ) {

//        $option = [
//            'from' => in('from'),
//            'limit' => in('limit'),
//            'where' => in('where'),
//            'bind' => in('bind'),
//            'order' => in('order')
//        ];
//
//        $option = $this->getSearchVariables();
//                                        if ( is_error( $option ) ) return error( $option );
//        $post_configs = parent::search();
//                                        if ( is_error( $post_configs ) ) return error( $post_configs );

        $re = parent::search(); if ( is_error( $re ) ) return error( $re );
        $post_configs = $re[0];
        $option = $re[1];
        success( [
            'total' => parent::countSearch(),
            'configs' => $post_configs,
            'page' => $option['page'],
            'limit' => $option['limit']
        ] );

    }




}