<?php

namespace model\category;
class Category_Interface extends Category
{


    /**
     *
     * @param null $_
     * @return void
     */
    public function create( $_=null ) {


        $record['parent_idx'] = in('parent_idx', 0);

        $record['id'] = in('id');
        $record['name'] = in('name');
        $record['description'] = in('description');

        $re = parent::create( $record );
        if ( is_error($re) ) error( $re );
        else success( ['idx' => $re] );

    }
}
