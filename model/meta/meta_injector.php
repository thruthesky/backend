<?php
namespace model\meta;
class Meta_Injector extends Meta {

    private $model = null;
    private $model_idx = null;

    /**
     * Meta_Injector constructor.
     * @param $model
     * @param $model_idx
     */
    public function __construct( $model, $model_idx )
    {
        parent::__construct();
        $this->model = $model;
        $this->model_idx = $model_idx;
    }

    /**
     * @param $code
     *          if it is an array, then it sets array of meta on the Entity.
     * @param null $data
     * @return bool|int|number
     */
    public function set( $code, $data = null) {

        if ( is_array($code) ) return $this->create( $this->model, $this->model_idx, $code );
        else {
            parent::set( 'model', $this->model );
            parent::set( 'model_idx', $this->model_idx );
            parent::set( 'code', $code );
            parent::set( 'data', $data );
            return $this->create( $this->model, $this->model_idx, [ $code => $data ] );
        }

    }

    /**
     * Alias of parent::get()
     *
     * @param null $code
     * @param null $_
     * @param null $__
     * @return array
     */
    public function get( $code = null, $_ = null , $__ = null ) {
        return parent::get( $this->model, $this->model_idx, $code );
    }

    public function delete() {

    }

}