<?php

namespace model\meta;
class Meta_Interface extends Meta {


    /**
     *
     *
     * HTTP interface
     *
     * @param null $_
     * @param null $__
     * @param null $___
     * @return void
     *
     */
    public function create( $_=null, $__=null, $___=null ) {
        $idx = meta()
            ->set('model', in('model'))
            ->set('model_idx', in('model_idx'))
            ->set('code', in('code'))
            ->set('data', in('data'))
            ->create();
        if ( is_error( $idx ) ) error( $idx );
        else success( ['meta'=> meta( $idx )->getRecord() ] );
    }



    /**
     * HTTP interface
     * @param Meta $meta
     * @param null $_
     * @param null $__
     * @return void
     */
    public function delete( $meta=null, $_=null, $__=null ) {

        $idx = $meta->idx;
        $re = $meta->delete();

        if ( is_error($re) ) error( $re );
        else success( [ 'idx' => $idx ] );

    }


    /**
     * @param null $_
     * @return mixed
     */
    public function search( $_=null ) {

        /// Search for meta data.
        /// One can search only one's meta.
        ///
        $_REQUEST['where'] = 'user_idx=? AND ' . $_REQUEST['where'];
        $_REQUEST['bind'] = currentUser()->idx . ',' . $_REQUEST['bind'];
        $re = parent::search(); if ( is_error( $re ) ) return error( $re );
        $meta = $re[0];
        $option = $re[1];
        success( [
            'total' => parent::countSearch(),
            'meta' => $meta,
            'page' => $option['page'],
            'limit' => $option['limit']
        ] );

    }

}