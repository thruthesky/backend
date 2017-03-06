<?php


/**
 *
 */

namespace model\meta;
class Meta extends \model\base\Base
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('meta');
    }


    /**
     * Create or Update meta data.
     *
     * @param $model
     * @param $model_idx
     * @param $code
     * @param $data
     * @return number
     *                  - meta.idx will be return on success.
     *                  - ERROR CODE WILL BE return on error.
     */
    public function set( $model, $model_idx, $code, $data ) {


        db()->delete( 'meta', " model='$model' AND model_idx = $model_idx AND code='$code' " );

        $kvs = [
            'model' => $model,
            'model_idx' => $model_idx,
            'code' => $code,
            'data' => $data
        ];
        $idx = db()->insert( 'meta', $kvs );
        if ( empty($idx) ) error(ERROR_DATABASE_INSERT_FAILED);
        return $idx;

    }


    /**
     *
     * Creates or Updates multiple meta code/data
     *
     * @param $model
     * @param $model_idx
     * @param $code_data_array
     * @return bool
     */
    public function sets( $model, $model_idx, $code_data_array ) {

        if ( ! is_array( $code_data_array ) ) {
            debug_log("meta::sets() $code_data_array is not an array");
            return false;
        }
        foreach ( $code_data_array as $code => $data ) {
            $this->set( $model, $model_idx, $code, $data );
        }
        return true;

    }


    /**
     * Return a data from a record.
     * @param $model
     * @param $model_idx
     * @param $code
     * @return mixed
     */
    public function get( $model, $model_idx, $code ) {
        return db()->result(" SELECT data FROM meta WHERE $model = '$model' AND model_idx=$model_idx AND code='$code'");
    }

    /**
     *
     * Returns all code/data of model and model_idx
     *
     * @param $model
     * @param $model_idx
     * @return array|int
     */
    public function gets( $model, $model_idx ) {
        return db()->rows(" SELECT code, data FROM meta WHERE model='$model' AND model_idx=$model_idx");
    }


    /**
     * Deletes one(1) record of model, model_idx, code.
     * @param $model
     * @param $model_idx - default is null for parent::delete() argument match.
     * @param $code - default is null for parent::delete() argument match.
     */
    public function delete( $model, $model_idx=null, $code=null ) {

        parent::delete( "model = '$model' AND model_idx = $model_idx AND code = '$code'" );

//        db()->query("DELETE FROM meta WHERE model = '$model' AND model_idx = $model_idx AND code = '$code'");

    }



    /**
     * DELETES all the data of 'model' and its idx.
     * @param $model
     * @param $model_idx
     */
    public function deletes( $model, $model_idx ) {

        parent::delete( "model = '$model' AND model_idx = $model_idx" );

        //db()->query("DELETE FROM meta WHERE model = '$model' AND model_idx = $model_idx");
    }




    public function getCount( $model, $model_idx, $code ) {

        return $this->count("model = '$model' AND model_idx = $model_idx AND code = '$code' AND model = '$model'");

        //parent::count( "model = '$model' AND model_idx = $model_idx AND code = '$code'" );
        // return db()->result("SELECT COUNT(*) FROM meta WHERE model = '$model' AND model_idx = $model_idx AND code = '$code'");

    }

}
