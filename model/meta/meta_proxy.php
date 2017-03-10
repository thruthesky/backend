<?php
namespace model\meta;
class Meta_Proxy {

    private $model = null;
    private $model_idx = null;

    /**
     * Meta_Injector constructor.
     * @param $model
     * @param $model_idx
     */
    public function __construct( $model, $model_idx )
    {
        $this->model = $model;
        $this->model_idx = $model_idx;
    }

    /**
     * @param $code
     *          if it is an array, then it sets array of meta on the Entity.
     * @param null $data
     * @return mixed - Same as entity()->create()
     */
    public function set( $code, $data = null) {


        if ( is_array($code) ) return meta()->create( $this->model, $this->model_idx, $code );
        else {
            return meta()
                ->set( 'model', $this->model )
                ->set( 'model_idx', $this->model_idx )
                ->set( 'code', $code )
                ->set( 'data', $data )
                ->create();
        }

    }

    /**
     * Alias of parent::get()
     *
     * @param null $code
     * @return array
     */
    public function get( $code = null ) {
        return meta()->get( $this->model, $this->model_idx, $code );
    }

    /**
     * @param null $code
     * @return int
     */
    public function delete( $code = null ) {

        return meta()->delete( $this->model, $this->model_idx, $code );

    }

}