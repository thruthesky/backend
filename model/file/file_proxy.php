<?php
namespace model\file;
class File_Proxy extends File {

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
     * Alias of parent::get()
     *
     *
     * @param null $code
     * @param null $__
     * @param null $___
     * @return array
     */
    public function get( $code = null, $__=null, $___=null ) {
        //return f()->getRecords( " model='$this->model' AND model_idx=$this->model_idx " );
        return parent::get( $this->model, $this->model_idx, $code );
    }


    /**
     * proxy to parent
     * @param null $code
     * @param null $__
     * @param null $___
     * @return array|int
     */
    public function getFirstImage( $code = null, $__=null, $___=null ) {
        return parent::getFirstImage( $this->model, $this->model_idx, $code );
    }


    public function loadFirstImage( $code = null, $__=null, $___=null  ) {
        return parent::loadFirstImage( $this->model, $this->model_idx, $code );
    }



}