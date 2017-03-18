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
        $this->delete_test();
        $this->hook();
        $this->optimization();


        $this->unique();
    }

    public function input(){
//        $re = $this->route('upload');
//        test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model is empty test'  .get_error_string($re));

        //$params = ['model'=>111];
        //$re = $this->route('upload',$params);
        //test(is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, 'Model_idx is empty test'  .get_error_string($re));

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
        $re = f()->save( $_REQUEST, $_FILES['userfile']);
        test( is_error($re) == ERROR_UPLOAD_ERROR_NOT_SET, 'upload error must be set. even on success. :'  .get_error_string($re));

        // set file upload error to 0 which means no error.
        $_FILES['userfile']['error'] = 0;

        // wrong source file.
        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg' . 'wrong';
        $re = f()->save( $_REQUEST, $_FILES['userfile']);
        test( is_error($re) == ERROR_UPLOAD_FILE_NOT_EXIST, 'file is not upload uploaded because of wrong file name:'  .get_error_string($re));



        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';
        $re = f()->save( $_REQUEST, $_FILES['userfile']);

        test(is_success($re), 'Upload test'  .get_error_string($re));

        //
        $file = f ( $re );
        test( $file->exist(), "check if file uploaded: $re");
        $path = f()->path( $re );
        test( file_exists( $path ), "file existence check : $path");

        $file->delete();
        test( f($re)->exist() == false,"file delete test ($re)");
        test( ! file_exists( $path ), "file should be deleted : $path");
//        $re = $this->route('upload',$params);
    }

    public function delete_test(){

        $_REQUEST = ['model'=>111, 'model_idx'=>222];
        $_FILES['userfile']['size'] = 12345;
        $_FILES['userfile']['type'] = 'image/jpeg';
        $_FILES['userfile']['name'] = 'person.jpg';


        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';
        $re = f()->save( $_REQUEST, $_FILES['userfile']);
        test(is_success($re), "delete_test::Upload  with model idx $re"  .get_error_string($re));



        f()->deleteBy(111,222);
        //test if file exist
        $file = f ( $re );
        test( ! $file->exist(), "delete_test::file should not exist: $re");
        $path = f()->path( $re );
        test( ! file_exists( $path ), "delete_test::file path should not exist : $path");



        for( $i=0; $i<3; $i ++) {
        $_REQUEST = ['model'=>333, 'model_idx'=>444, 'code'=> "abc$i" ];
        $_FILES['userfile']['size'] = 12345;
        $_FILES['userfile']['type'] = 'image/jpeg';
        $_FILES['userfile']['name'] = 'person.jpg';

        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';
        $re = f()->save( $_REQUEST, $_FILES['userfile']);
        test( is_success( $re ), "delete_test::Upload with code $re"  .get_error_string($re));
        $path = f()->path( $re );
        test( file_exists( $path ), "delete_test::file path should exist : $path");
        }
        
        $re = f()->count( 333, 444);
        test( $re == 3, '3 files are successfully uploaded' );

        f()->deleteBy( 333, 444, 'abc0');
        $re = f()->count( 333, 444);
        test( $re == 2, '1 file deleted. there should be 2 files left.' );



        f()->deleteBy(333, 444 );
        $re = f()->count( 333, 444);
        test( $re == 0, 'all model 333 and model_idx 444 should be deleted ' );
    }


    public function unique() {
        $file_idx = $this->createFile('test', 1, 'code1');
        $file_idx = $this->createFile('test', 1, 'code1');
        $re = f()->count( 'test', 1, 'code1');
        test( $re == 2, "two file created.");

        $file_idx = $this->createFile( 'test', 1, 'code1', 'Y' );
        $re = f()->count( 'test', 1, 'code1');
        test( $re == 1, "two file deleted and 1 file left since it is created as unique.");

        f()->deleteBy( 'test', 1, 'code1' );
    }

    public function hook() {

        $file_idx = $this->createFile( 'user', 3, BACKEND_PRIMARY_PHOTO);


        $re = $this->createUser([
            'id' => 'hook-' . date('his'),
            'password' => 'pass',
            'file_hooks' => [ $file_idx ]
        ]);

        $user = user( $re );

        $file = new \model\file\File();
        $file->load( $file_idx );

        test( $file->user_idx == $user->idx, "File hook test: ");
    }
    public function optimization() {

    }
}
