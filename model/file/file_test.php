<?php

namespace model\file;

class File_Test extends \model\test\Test {
    public function __construct()
    {
        parent::__construct();
    }

    public function run(){
        $this->input();
        $this->save_test();
    }

    public function input(){
        $re = $this->route('upload');
        test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model is empty test'  .get_error_string($re));

        $params = ['model'=>111];
        $re = $this->route('upload',$params);
        test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model_idx is empty test'  .get_error_string($re));

        //has model and para
        $params = ['model'=>111, 'model_idx'=>222];
        $re = $this->route('upload',$params);
        test( is_error($re) != ERROR_REQUIRED_INPUT_IS_MISSING, 'input test'  .get_error_string($re));
    }

    public function save_test() {

        $_REQUEST = ['model'=>111, 'model_idx'=>222];
        $_FILES['userfile']['size'] = 12345;
        $_FILES['userfile']['type'] = 'image/jpeg';
        $_FILES['userfile']['name'] = 'person.jpg';

        // error test
        $re = f()->save($_FILES['userfile']);
        test( is_error($re) == ERROR_UPLOAD_ERROR_NOT_SET, 'upload error must be set. even on success. :'  .get_error_string($re));

        // set file upload error to 0 which means no error.
        $_FILES['userfile']['error'] = 0;

        // wrong source file.
        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg' . 'wrong';
        $re = f()->save($_FILES['userfile']);
        test( is_error($re) == ERROR_UPLOAD_FILE_NOT_EXIST, 'file is not upload uploaded because of wrong file name:'  .get_error_string($re));



        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';
        $re = f()->save($_FILES['userfile']);

        test(is_success($re), 'Upload test'  .get_error_string($re));

        //
        $file = f ( $re );
        test( $file->exist(), "check if file uploaded: $re");
        $path = f()->path( $re );
        test( file_exists( $path ), "file existence check : $path");



        

//        $re = $this->route('upload',$params);
    }

}
