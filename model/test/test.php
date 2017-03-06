<?php
namespace model\test;
class Test {
    public function run() {


        $files = rsearch( __MODEL_DIR__, '_test.php' );

        foreach ( $files as $file ) {


            $file = str_replace(".php", '', $file);
            $arr = array_reverse( preg_split( "/[\\\\\/]/", $file));

            $path = "model\\$arr[1]\\$arr[0]";


            $obj = new $path();

            if ( method_exists( $obj, 'run' ) ) $obj->run();
        }

        exit;

    }
}