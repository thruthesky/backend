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
     * Return a data from a record.
     * @param $model
     * @param $model_idx
     * @param $code
     * @return \model\meta\Meta
     */
    /*
    public function load( $model=null, $model_idx=null, $code=null ) {

        if ( is_numeric($model) && $model_idx === null && $code === null ) return parent::load( $model );
        return $this->loadQuery( "model = '$model' AND model_idx='$model_idx' AND code='$code'" );
        // return db()->result(" SELECT data FROM meta WHERE $model = '$model' AND model_idx=$model_idx AND code='$code'");
    }
    */


    /**
     * Overrides entity create()
     *
     * @note Returns number higher than 0 as "entity.idx" if a single record is created/inserted without error.
     *
     * @note Returns number less than 0 if there is any error with single record creation.
     *
     * @note Returns TRUE if multi record is created without error. Use === to check.
     *
     * @note Returns FALSE if if there is any error on multi record create. Use === to check.
     *
     *
     * @param null $model_idx
     * @param null $code
     * @param null $data
     * @return int|number
     */
    /*
    public function create( $model_idx=null, $code=null, $data=null ) {



        if ( $model_idx === null || is_array( $model_idx ) ) {
            return parent::create( $model_idx );
        }

        if ( is_string($code) && $data ) {   // single record create.
            return meta()
                ->set('model', $this->getTable() )
                ->set('model_idx', $model_idx)
                ->set('code', $code)
                ->set('data', $data)
                ->create();
        }

        // multi record creation.

        $multi_re = TRUE;
        if ( is_array( $code ) && $data === null ) {
            foreach( $code as $k => $v ) {
                $idx = meta()->create( $model_idx, $k, $v );        // Re-call.
                if ( $idx < 0 ) $multi_re = FALSE;
            }
        }

        return $multi_re;
    }

    */



    /**
     * Create or Update meta data.
     *
     * @attention It is a unique record.
     *
     * @param $model
     * @param $model_idx
     * @param $code
     * @param $data
     * @return number
     *                  - number higher than 0 as meta.idx on success.
     *                  - number lower than 0 as ERROR on error.
     */
    /*
    public function set( $model, $model_idx, $code=null, $data=null ) {


        db()->delete( 'meta', " model='$model' AND model_idx = '$model_idx' AND code='$code' " );

        $record = [
            'model' => $model,
            'model_idx' => $model_idx,
            'code' => $code,
            'data' => $data
        ];
        $idx = meta()->create( $record );
        return $idx;

    }
    */


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
     * @note Returns number higher than 0 as "entity.idx" if a single record is created/inserted without error.
     *
     * @note Returns number less than 0 if there is any error with single record creation.
     *
     * @note Returns FALSE(0, OK) if multi record is created without error. Use === to check.
     *
     * @note Returns ERROR_META_MULTI_CREATE_FAILED if if there is any error on multi record create. Use === to check.
     *
     *
     *
     *
     * @param null $model
     * @param null $model_idx
     * @param null $arr
     * @return bool|int|number
     */
    public function create( $model = null, $model_idx = null, $arr = null ) {


        if ( is_array( $arr ) ) {
            $re_multi = OK;
            foreach( $arr as $k => $v ) {
                $idx = meta()
                    ->set( 'model', $model )
                    ->set( 'model_idx', $model_idx )
                    ->set( 'code', $k )
                    ->set( 'data', $v )
                    ->create();
                if ( $idx < 0 ) $re_multi = ERROR_META_MULTI_CREATE_FAILED;
            }
            return $re_multi;
        }

        $model = $this->model;
        $model_idx = $this->model_idx;
        $code = $this->code;

        if ( empty($model) || empty($model_idx) || empty($code) ) return ERROR_WRONG_META_DATA_TO_CREATE;

        meta()->load( $model, $model_idx, $code )->delete();


        return parent::create();
    }

    /**
     *
     * Creates or Updates multiple meta code/data
     *
     * @param $model
     * @param $model_idx
     * @param $code_data_array
     * @return bool
     *          TRUE on success if there is no array while creating all the meta.
     *          or else, FALSE.
     */
    /*
    public function sets( $model, $model_idx, $code_data_array ) {

        if ( ! is_array( $code_data_array ) ) {
            debug_log("meta::sets() $code_data_array is not an array");
            return false;
        }

        $re = true;
        foreach ( $code_data_array as $code => $data ) {
            $idx = $this->set( $model, $model_idx, $code, $data );
            if ( $idx < 0 ) $re = false;
        }
        return $re;

    }
    */


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
     * Returns all code/data of model and model_idx
     *
     * @deprecated user method 'get()'
     *
     * @param $model
     * @param $model_idx
     * @return array - associative array.
     *
     *
     * @code Example return

            [ 'key' => 'value', 'name' => 'JaeHo Song' ]

     * @endcode
     */
    public function gets( $model, $model_idx ) {

        $ret = [];

        $rows = db()->rows(" SELECT code, data FROM meta WHERE model='$model' AND model_idx=$model_idx");
        if ( empty( $rows ) ) return $ret;
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
     * DELETES all the data of 'model' and its idx.
     * @param $model
     * @param $model_idx
     */
    public function deletes( $model, $model_idx ) {

        return db()->delete( $this->getTable(), "model = '$model' AND model_idx = $model_idx" );

        //db()->query("DELETE FROM meta WHERE model = '$model' AND model_idx = $model_idx");
    }



    public function count( $model, $model_idx=null, $code=null ) {

        if ( $model_idx && $code ) return parent::count("model = '$model' AND model_idx = $model_idx AND code = '$code'");
        else if ( $model_idx )  return parent::count("model = '$model' AND model_idx = $model_idx");
        else return parent::count("model = '$model'");
    }

}
