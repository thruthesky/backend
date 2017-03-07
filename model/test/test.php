<?php
namespace model\test;
class Test {




    public function run() {


        $this->textRoute();

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

    private function textRoute() {
        $re = $this->route("version");
        test ( is_success( $re ), "Route Access OK. " . get_error_string($re));
    }


    /**
     *
     * @param $route
     * @param array $params
     * @return mixed
     */
    public function route( $route, $params = [] ) {

        $_REQUEST = $params;
        $re = $this->http_get( $route );
        if ( empty($re) ) return [ 'code' => -999, 'message' => 'No data received' ];
        return $re;
    }


    public function http_get($route) {
        $body = file_get_contents("http://localhost/www/backend-0.2/?route=$route");
        return json_decode($body, true);
    }

    /**
     * this returns random string.
     * @param int $length
     * @return string
     */

    function randomString($length = 1) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z') );
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }


}