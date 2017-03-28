<?php
namespace model\test;
class Test {
    static $count = 0;
    static $count_success = 0;
    static $count_error = 0;
    static $done_test_info = false;
    static $time_start = 0;
    static $reload = 100;
    public function __construct( $reload = 15 )
    {
        self::$reload = $reload;
        if ( ! self::$time_start ) self::$time_start = microtime(true);
        set_test();
        $this->test_style();
        $this->test_info();
    }

    public function __destruct()
    {
        $this->test_reload( self::$reload );
        $this->test_end();
    }

    public static function test($re, $code)
    {
        self::$count ++;
        if ( is_array($re) ) {
            if ( isset($re['code']) && ! isset($re['idx']) ) { // server data.
                if ( $re['code'] ) self::test_error($re, $code);
                else self::test_success($re, $code);
            }
            else { // unknown data.
                if ( $re ) self::test_success($re, $code);
                else self::test_error( $re, $code );
            }
        }
        else { //
            if ( $re ) self::test_success($re, $code);
            else self::test_error( $re, $code );
        }

    }
    public static function test_success($re, $code) {
        self::$count_success ++;
        echo "<div class='success'>" . self::$count . " - SUCCESS: $code</div>";
    }

    function test_error( $re, $code ) {
        self::$count_error ++;
        echo "<div class='error'><span style='color:red; font-weight: bold;'>" . self::$count . "- ERROR</span> $code</div>";
        echo "<pre>";
        debug_print_backtrace();
        echo "</pre>";
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
                var timeout_reload;
                if ( timeout_reload ) clearTimeout(timeout_reload);
                timeout_reload = setTimeout( function() {
                    location.reload( true );
                }, $seconds );
            </script>
        ";
    }
    public function test_style() {
        echo "
            <style>
                body { font-size: 10pt; }
                .test-info { padding: 1em; background-color: #c5cdd9; }
                .break { margin-top: 1em; font-weight: bold; }
                .error { font-size: 13pt; text-decoration: underline; }
            </style>
        ";
    }
    public function test_end() {

        $time_end = round(microtime(true) - self::$time_start, 3);
        $success = self::$count_success;
        $error = self::$count_error;
        if ( $error > 0 ) $error = "[[[[[[[[[[[[[[[[[[[ $error ]]]]]]]]]]]]]]]]]]]";

        echo <<<EOH
<script>
    var qs = document.querySelector('.test-result');
    qs.innerText="Success: $success, Error: $error Time: $time_end";
</script>
EOH;

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
        if ( self::$done_test_info ) return;
        self::$done_test_info = true;
        echo "<div class='test-info'>";
        echo "<div>Database Type: ". DATABASE_TYPE ."</div>";
        echo "<div>Database Name: ". DATABASE_NAME ."</div>";
        echo "<div class='test-result'>...</div>";
        echo "</div>";

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
        route()->run( $route );
        $data = ob_get_clean();

//di($data);
        if ( empty($data) ) return [ 'code' => ERROR_NO_RESPONSE, 'message' => 'No data received' ];


        $res = json_decode($data, true);
        if ( $this->is_json_error() ) {
            echo "<pre>js_error:";
            echo $data;
            echo "</pre>";
            return ['code' => ERROR_JSON_PARSE, "message" => "JSON parse error. This may be a server error or PHP script error/warning"];
        }


        $_REQUEST = [];
        return $res;
    }


    /**
     *
     * Returns ERROR_JSON_PARSE if there was any error while JSON decoding.
     * @return int
     */
    private function is_json_error() {

        switch (json_last_error()) {
            case JSON_ERROR_NONE: return OK;
            case JSON_ERROR_DEPTH: return ERROR_JSON_PARSE;
            case JSON_ERROR_STATE_MISMATCH: return ERROR_JSON_PARSE;
            case JSON_ERROR_CTRL_CHAR: return ERROR_JSON_PARSE;
            case JSON_ERROR_SYNTAX: return ERROR_JSON_PARSE;
            case JSON_ERROR_UTF8: return ERROR_JSON_PARSE;
            default: return ERROR_JSON_PARSE;
        }

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

        if ( is_error($re) ) {
            test(is_success($re), "Create a user failed: $record[id]"  .get_error_string($re));
        }
        return is_success($re) ? $re['data']['session_id'] : null;

    }


    public function deleteUser( $session_id ) {

        // resign
        $re = $this->route('resign', [ 'session_id' => $session_id ] );
        // test( is_success($re), "User resign success: " . get_error_string($re));

    }

    public function createFile( $model, $model_idx, $code = null, $unique="N" ) {

        $_REQUEST = ['model'=> $model, 'model_idx'=> $model_idx, 'code' => $code, 'unique' => $unique ];
        $_FILES['userfile']['size'] = 12345;
        $_FILES['userfile']['type'] = 'image/jpeg';
        $_FILES['userfile']['name'] = 'person.jpg';
        $_FILES['userfile']['tmp_name'] = __ROOT_DIR__ . '/tmp/person.jpg';
        $_FILES['userfile']['error'] = 0;
        $re = f()->save( $_REQUEST, $_FILES['userfile']);
        if ( is_error($re) ) {
            test(is_success($re), "Create/upload failed: $re"  .get_error_string($re));
        }
        return $re;

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
        if ( is_error($re) ) test( is_success( $re ), "test::createPostConfig() " . get_error_string( $re ) );

    }


    public function createComment( $param ) {

        $re = $this->route( 'post_comment.create', $param );
        if ( is_error($re) ) test( false, "createComment: " . get_error_string($re));
        else return $re['data']['idx'];

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

    public function  createCategory( $record, $replace=true ) {

        if ( empty( $record['id'] ) ) test( false, "crateCategory: input id to create a category");
        if ( $replace ) category( $record['id'] )->delete();
        $re = $this->route('category.create', $record);
        if ( is_error($re) ) test(false, "createCategory: " . get_error_string($re));
        return $re['data']['idx'];

    }



}