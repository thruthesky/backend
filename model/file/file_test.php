<?php

namespace model\file;

class File_Test extends \model\test\Test {
    public function __construct()
    {
        parent::__construct();
    }

    public function run(){
        $re = $this->route('upload');
        test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model is empty test'  .get_error_string($re));

        $params = ['model'=>111];
        $re = $this->route('upload',$params);
        test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model is empty test'  .get_error_string($re));

        //has model and para
        $params = ['model'=>111, 'model_idx'=>222];
        $re = $this->route('upload',$params);
        //test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model is empty test'  .get_error_string($re));

        $_FILES['userfile']['size'] = 12345;
        $_FILES['userfile']['type'] = 'image/jpeg';
        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';

        $re = $this->route('upload',$params);

    }
}
