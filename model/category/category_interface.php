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


        $record['model'] = in('model');
        $record['model_idx'] = in('model_idx');


        $re = parent::create( $record );
        if ( is_error($re) ) error( $re );
        else success( ['idx' => $re] );

    }


    /**
     * @param Category $category
     */
    public function edit( $category ) {
        $re = $category
            ->update([
                'model' => in('model'),
                'model_idx' => in('model_idx'),
                'name' => in('name'),
                'description' => in('description'),
            ]);

        if ( is_error($re) ) error( $re );
        else success( [] );

    }


    /**
     * @param Category $category
     * @return mixed|number
     */
    public function delete( $category=null ) {

        $re = $category->delete();

        if ( is_success( $re ) ) success( ['idx'=>in('idx')] );
        else error( $re );


    }

}
