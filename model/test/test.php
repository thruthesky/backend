<?php
namespace model\test;
class Test {

    public function __construct()
    {
        $this->test_style();
        $this->test_reload( 2 );
        $this->test_info();

    }

    public function runAllTests() {


        $this->textRoute();

        $files = rsearch( __MODEL_DIR__, '_test.php' );
        include __ROOT_DIR__ . '/etc/test_config.php';



        $new_files = [];
        global $_config;

        foreach( $_config['test_order'] as $folder ) {
            for ( $i = 0; $i < count($files); $i ++ ) {
                $file = $files[$i];
                if ( empty($file) ) continue;
                $arr = array_reverse( preg_split( "/[\\\\\/]/", $file));
                if ( $arr[1] == $folder ) {
                    $new_files[] = $file;
                    $files[$i] = null;
                }
            }
        }
        foreach( $files as $file ) {
            if ( $file ) $new_files[] = $file;
        }




        foreach ( $new_files as $file ) {


            $file = str_replace(".php", '', $file);
            $arr = array_reverse( preg_split( "/[\\\\\/]/", $file));

            $path = "model\\$arr[1]\\$arr[0]";


            $obj = new $path();

            if ( method_exists( $obj, 'run' ) ) {
                $this->test_break( $path );
                $obj->run();
            }
        }



    }

    public function test_reload( $n ) {
        $seconds = $n * 1000;
        echo "
            <script>
                setTimeout( function() {
                    location.reload( true );
                }, $seconds );
            </script>
        ";
    }
    public function test_style() {
        echo "
            <style>
                body { font-size: 10pt; }
                .break { margin-top: 1em; font-weight: bold; }
                .error { font-size: 13pt; text-decoration: underline; }
            </style>
        ";
    }
    public function test_break( $path ) {
        echo "
        <div class='break'>------------- $path -------------</div>
        ";
    }
    private function textRoute() {
        $re = $this->route("version");
        test ( is_success( $re ), "Route Access OK. " . get_error_string($re));

    }

    private function test_info() {
        echo "<div>Database Type: ". DATABASE_TYPE ."</div>";
        echo "<div>Database Name: ". DATABASE_NAME ."</div>";
    }

    /**
     *
     * /**
     *
     */
    public function route( $route_name, $params = [] ) {

        $_REQUEST = $params;

        ob_start();
        $route = get_route( $route_name );
        if ( empty($route) ) return ['code' => ERROR_ROUTE_NOT_EXIST, 'message' => 'No route exists' ];
        run_route( $route );
        $data = ob_get_clean();
        if ( empty($data) ) return [ 'code' => ERROR_NO_RESPONSE, 'message' => 'No data received' ];
        $res = json_decode($data, true);

        $_REQUEST = [];
        return $res;
    }



    public function http_route( $route_name, $params = [] ) {

        $params['route'] = $route_name;
        $vars = http_build_query( $params );
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?$vars";


        $body = file_get_contents( $url );
        $re = json_decode( $body, true );

        return $re;

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



    public function getAdminSessionId() {

        // Get admin session id.
        $re = $this->route("login", ['id'=>ADMIN_ID, 'password'=>ADMIN_ID]);
        // test( is_success($re), "test::getAdminSessionId() admin login for config test: " . get_error_string($re));
        return $re['data']['session_id'];

    }

    public function getUserSessionId( $id ) {


        return user( $id )->getSessionId();

    }

    /**
     * @param $record
     * @return null
     *
     * @code
            $session_id = $this->createUser( ['id' => 'user4', 'password' => 'pass4']);
     * @endcode
     */
    public function createUser( $record ) {

        user( $record['id'] )->delete();

        $re = $this->route( "register", $record );
        return is_success($re) ? $re['data']['session_id'] : null;

    }
    public function deleteUser( $session_id ) {

        // resign
        $re = $this->route('resign', [ 'session_id' => $session_id ] );
        // test( is_success($re), "User resign success: " . get_error_string($re));

    }



    public function createPostConfig( $post_config_id ) {

        $admin_session_id = $this->getAdminSessionId();

        // Delete test config if exists.
        $re = $this->route( 'post_config.data', ['id' => $post_config_id ] );
        // test( is_success($re), "post_config.data() id: $id" . get_error_string( $re ));
        if ( is_success($re) ) {
            $re = $this->route( 'post_config.delete', ['session_id' => $admin_session_id, 'id' => $post_config_id ] );
            test( is_success($re), "test::createPostConfig() deleted. " . get_error_string($re));
        }



        // Config create with admin session id. Expect: success
        $data = [];
        $data['id'] = $post_config_id;
        $data['session_id'] = $admin_session_id;
        $re = $this->route("post_config.create", $data );
        test( is_success( $re ), "test::createPostConfig() " . get_error_string( $re ) );

    }

    public function deletePostConfig( $post_config_id ) {

        $admin_session_id = $this->getAdminSessionId();

        // Delete. cleaning.
        $re = $this->route( 'post_config.data', ['id' => $post_config_id] );
        test( is_success($re), "test::deletePostConfig() id: $post_config_id" . get_error_string( $re ));
        if ( is_success($re) ) {
            $re = $this->route( 'post_config.delete', ['session_id' => $admin_session_id, 'id' => $post_config_id] );
            // test( is_success($re), "test::deletePostConfig() for cleaning: $post_config_id. " . get_error_string($re));
        }

    }


}