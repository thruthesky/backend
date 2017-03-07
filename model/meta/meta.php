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
    public function load( $model=null, $model_idx=null, $code=null ) {

        if ( is_numeric($model) && $model_idx === null && $code === null ) return parent::load( $model );
        return $this->loadQuery( "model = '$model' AND model_idx='$model_idx' AND code='$code'" );
        // return db()->result(" SELECT data FROM meta WHERE $model = '$model' AND model_idx=$model_idx AND code='$code'");
    }



    public function countOf( $model, $model_idx, $code ) {

        return $this->count("model = '$model' AND model_idx = $model_idx AND code = '$code' AND model = '$model'");

    }

}
