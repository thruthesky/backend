<?php
namespace model\file;
class File_Proxy {

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
     * Alias of parent::get()
     *
     *
     * @return array
     */
    public function get() {
        return f()->getRecords( " model='$this->model' AND model_idx=$this->model_idx " );
    }

}