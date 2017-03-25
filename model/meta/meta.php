<?php


/**
 *
 */

namespace model\meta;
class Meta extends \model\entity\Entity
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('meta');
    }






    /**
     *
     *
     * It supports the entity()->create()
     *
     * @code
    $idx = meta()
     * ->set('model', $model)
     * ->set('model_idx', $model_idx)
     * ->set('code', $code)
     * ->set('data', $data)
     * ->debug_log()
     * ->create();
     * @endcode
     *
     *
     *
     * Overrides entity create()
     *
     *
     *
     *
     *
     *
     * @param null $model
     * @param null $model_idx
     * @param null $arr
     * @return bool|int|number - Use is_success() to check if create was success.
     *
     * @note Returns number higher than 0 as "entity.idx" if a single record is created/inserted without error.
     * @note Returns number less than 0 if there is any error with single record creation.
     * @note Returns OK if multi record is created without error. Use === to check.
     * @note Returns ERROR if there is error on inserting a record.
     *
     *
     */
    public function create( $model = null, $model_idx = null, $arr = null ) {


        // if it creates 'multiple' meta, then it does recursive call.
        if ( is_array( $arr ) ) {

            if ( empty($model) ) return ERROR_MODEL_IS_EMPTY;
            if ( empty($model_idx) ) return ERROR_MODEL_IDX_IS_EMPTY;

            $re_multi = OK;
            foreach( $arr as $k => $v ) {
                $idx = meta()
                    ->set( 'model', $model )
                    ->set( 'model_idx', $model_idx )
                    ->set( 'code', $k )
                    ->set( 'data', $v )
                    ->create();                 // RE-CALL to create an array of metas.
                if ( is_error( $idx ) ) $re_multi = ERROR_META_MULTI_CREATE_FAILED;
            }
            return $re_multi;
        }

        // get data to create meta.
        $model = $this->model;
        $model_idx = $this->model_idx;
        $code = $this->code;

        if ( empty($model) ) return ERROR_MODEL_IS_EMPTY;
        if ( empty($model_idx) ) return ERROR_MODEL_IDX_IS_EMPTY;
        if ( empty($code) ) return ERROR_CODE_IS_EMPTY;


        // delete if model, idx, code exsits.
        $m = meta()->load( $model, $model_idx, $code );
        if ( $m->exist() ) {
            // debug_log( $m );
            $m->delete();
        }
        //meta()->load( $model, $model_idx, $code )->delete();


        return parent::create();
    }



    /**
     * Return a data from a record.
     *
     * @param $model
     * @param $model_idx
     * @param $code
     * @return $this
     */
    public function load( $model, $model_idx=null, $code=null ) {
        if ( $model_idx === null ) return parent::load( $model );
        else {
            $meta = $this->loadQuery("model = '$model' AND model_idx=$model_idx AND code='$code'");
            return $meta;
        }
    }

    /**
     *
     * Returns all code/data of model and model_idx in array.
     *
     *
     *
     * @param $model
     * @param $model_idx
     * @param null $code
     * @return string|array - associative array.
     *
     * @code Example return
     *                      ->get('a', 1, 'code') will return 'string data value'
     *                      ->get('a', 1) will return array [ 'key' => 'value', 'name' => 'JaeHo Song' ]
     * @endcode
     *
     */
    public function get( $model, $model_idx, $code=null ) {

        $ret = [];

        if ( $code ) $and_code = "AND code='$code'";
        else $and_code = null;

        $rows = db()->rows(" SELECT code, data FROM {$this->getTable()} WHERE model='$model' AND model_idx=$model_idx $and_code");
        if ( empty( $rows ) ) return $ret;


        if ( $code != null ) return $rows[0]['data'];
        foreach ( $rows as $row ) {
            $ret[ $row['code'] ] = $row['data'] ;
        }
        return $ret;
    }



    /**
     * Deletes one(1) record of model, model_idx, code.
     * @param $model
     * @param $model_idx - default is null for parent::delete() argument match.
     * @param $code - default is null for parent::delete() argument match.
     */
    /*
    public function delete( $model=null, $model_idx=null, $code=null ) {

        return db()->delete( $this->getTable(), "model = '$model' AND model_idx = $model_idx AND code = '$code'" );

//        db()->query("DELETE FROM meta WHERE model = '$model' AND model_idx = $model_idx AND code = '$code'");

    }
    */


    /**
     *
     * Deletes meta data.
     *
     * @note if $code is null, then it deletes ALL the meta data of 'model.idx'
     *
     * @note if $code is not null, then it only delete one record of the 'code' of 'model.idx'
     *
     * @param $model
     * @param $model_idx
     * @param null $code
     * @return int - use is_success() to check
     */
    public function delete( $model=null, $model_idx=null, $code=null ) {

        if ( $model === null ) return parent::delete();

        if ( empty($model) ) return ERROR_MODEL_IS_EMPTY;
        if ( empty($model_idx) ) return ERROR_MODEL_IDX_IS_EMPTY;


        if ( $code ) $and_code = "AND code='$code'";
        else $and_code = null;



        /*
        return meta()
            ->loadQuery("model = '$model' AND model_idx = $model_idx $and_code")
            ->delete(); // @attention: This may call meta->delete() recursively NOT the one of entity()->delete(). but still okay.
        */

        /**
         * Since it is multi row delete, it cannot use entity()->delete()
         */
        // return db()->delete( $this->getTable(), " model='$model' AND model_idx='$model_idx' $and_code ");


        $rows = db()->rows("SELECT idx FROM {$this->getTable()} WHERE model='$model' AND model_idx='$model_idx' $and_code ");
        if ( $rows ) {
            foreach( $rows as $row ) {
                $re = $this->load( $row['idx'] )->delete(); // // @attention: This may call meta->delete() recursively NOT the one of entity()->delete(). but still okay.
                if ( is_error( $re ) ) return ERROR;
            }
        }

        return OK;
    }



}
