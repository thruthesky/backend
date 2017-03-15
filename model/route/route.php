<?php
/**
 *
 */

namespace model\route;
class Route {


    static $routes = [];
    static $_current_route = null;

    public function __construct()
    {

    }


    public function loadRoutes() {

        $route_files = rsearch( __MODEL_DIR__, '_route.php' );
        foreach (  $route_files as $file ) {
            include $file;
        }

    }






    /**
     * @param $name
     * @return mixed
     *      return FALSE if there is no route.
     */
    public function getRoute( $name ) {
        if ( isset( self::$routes[ $name ] ) && self::$routes[ $name ] ) return self::$routes[ $name ];
        else return FALSE;
    }

    /**
     * @param $name
     * @param $option
     * @return $this
     */
    public function addRoute( $name, $option ) {
        self::$routes[ $name ] = $option;
        return $this;
    }


    /**
     * @param $route
     * @return $this
     */
    function set_current_route( $route ) {
        self::$_current_route = $route;
        return $this;
    }

    /**
     * @return null
     */
    function get_current_route( ) {
        return self::$_current_route;
    }



    /**
     *
     * HTTP 로 넘오 온 변수 중 'required' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
     *
     * @return array
     */
    public function get_keys_of_required_variables() {
        $route = $this->get_current_route();
        if ( ! isset( $route['variables'] ) ) return [];
        $variables = $route['variables'];
        if ( ! isset( $variables['required'] ) ) return [];
        return $variables['required'];
    }


    /**
     *
     * HTTP 로 넘오 온 변수 중 'optional' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
     *
     * @return array
     */
    public function get_keys_of_optional_variables() {
        $route = $this->get_current_route();
        if ( ! isset( $route['variables'] ) ) return [];
        $variables = $route['variables'];
        if ( ! isset( $variables['optional'] ) ) return [];
        return $variables['optional'];
    }


    /**
     *
     * HTTP 로 넘오 온 변수 중 'system' 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
     *
     * @return array
     */
    public function get_keys_of_system_variables() {
        $route = $this->get_current_route();
        $system = [];
        if ( array_key_exists( 'variables', $route ) ) {
            $variables = $route['variables'];
            if ( array_key_exists( 'system', $variables ) ) $system = $variables['system'];
        }



        /**
         *
         *
         * 'route' variable is accepted by default since all access needs a route.
         *
         */
        if ( ! in_array( 'route', $system ) ) $system[] = 'route';

        return $system;
    }


    /**
     *
     *
     * HTTP 로 넘어 온 변수들의 '키'만 배열로 리턴한다. 값은 포함되지 않는다.
     *
     * @return array
     */
    public function get_keys_of_variables() {

        $all = array_merge(
            $this->get_keys_of_required_variables(),
            $this->get_keys_of_optional_variables(),
            $this->get_keys_of_system_variables()
        );

        return $all;
    }

    /**
     *
     *
     * Checks if the HTTP variables are properly passed.
     *
     *
     *
     * @return array|bool
     *
     */
    public function check_http_variables_existency() {

        if ( $required = $this->get_keys_of_required_variables()) {
            foreach ( $required as $v ) {
                if ( array_key_exists( $v, $_REQUEST ) ) {

                }
                else {
                    return [ 'code' => ERROR_REQUIRED_INPUT_IS_MISSING, 'message' => "required-variable-is-missing $v" ];
                }
            }
        }

        $variables = $this->get_keys_of_variables();
        foreach( array_keys( $_REQUEST ) as $key ) {
            if ( in_array( $key, $variables ) ) {

            }
            else {
                return [ 'code' => ERROR_INVALID_INPUT_VARIABLE, 'message' => "$key is-passed-over-http-but-NOT-in-route-variables"];
            }
        }
        return OK;
    }


    /**
     * HTTP Variable validation
     *
     * @return array|int
     *
     */
    public function validate_http_variables() {

        $number_if_contain = [ 'idx' ];
        $string = [ 'route', 'session', 'id' ]; // cannot be empty if passed.

        // title, content, data can be a numeric, string, empty, boolean.


        //$keys = get_route_variables();

        foreach( $_REQUEST as $k => $v ) {

            foreach ( $number_if_contain as $contain ) {
                if ( strpos( $k, $contain ) !== false ) {
                    if ( ! is_numeric($v) ) return [ 'code' => ERROR_MALFORMED_VARIABLE_NUMBER, 'message' => "variable $k must be number"];
                }
            }

            if ( in_array( $k, $string ) ) {
                if ( empty($v) ) return [ 'code' => ERROR_VARIABLE_EMPTY, 'message' => "variable $k must not be empty"];
                if ( is_numeric($v) ) return [ 'code' => ERROR_VARIABLE_NUMERIC, 'message' => "variable $k must not be numeric"];
                if ( is_array($v) ) return [ 'code' => ERROR_VARIABLE_ARRAY, 'message' => "variable $k must not be array"];
            }

            if ( $k == 'id' ) {
                if ( preg_match( '/^[\w@.-]{3,64}$/', $v ) ) {

                }
                else {
                    return [ 'code' => ERROR_MALFORMED_ID, 'message'=> 'id-has-malformed' ];
                }
            }
        }


        return OK;
    }



    /**
     *
     * Returns an array of HTTP variables ( GET, POST ) based on route's 'required' and 'optional' without 'system'
     *
     * If you need to get only key, use 'get_keys_of_variables()'.
     *
     * @translate-korean HTTP 로 전달된 GET, POST 의 키와 값을 배열로 리턴한다. 키만 필요한 경우 get_keys_of_variables() 를 사용한다.
     *
     * @note use get_keys_
     *
     * @note this returns Actual HTTP Data.
     *
     * @return array
     *
     */
    public function get_route_variables( ) {

        $ret = [];
        $route = $this->get_current_route();

        if ( ! isset( $route['variables'] ) ) return $ret;

        $variables = $route['variables'];


        $required = $optional = [];
        if ( isset( $variables['required'] ) ) $required = $variables['required'];
        if ( isset( $variables['optional'] ) ) $optional = $variables['optional'];
        $accepts = array_merge( $required, $optional );

        foreach( $accepts as $k ) {
            if ( array_key_exists( $k, $_REQUEST ) ) $ret[ $k ] = $_REQUEST[ $k ];
        }
        return $ret;
    }

    /**
     *
     * Returns an array of HTTP input based on route's 'optional' variable.
     *
     * @return array
     */
    public function get_route_optional_variables() {
        $ret = [];
        $route = $this->get_current_route();

        if ( ! isset( $route['variables'] ) || ! isset( $route['variables']['optional'] ) ) return $ret;

        $optional = $route['variables']['optional'];
        foreach( $optional as $k ) {
            if ( array_key_exists( $k, $_REQUEST ) ) $ret[ $k ] = $_REQUEST[ $k ];
        }
        return $ret;
    }


///// run the class method
    function run( $route )
    {

        if ( ! $route ) return error( ERROR_ROUTE_NOT_EXIST );
        $this->set_current_route( $route );
        if (class_exists($route['path'])) {      // if class file ('model/model-name/class.php') exists?
            $obj = new $route['path']();                 // __constructor() runs.
            if (isset($route['method'])) { // if method is to be called?
                if (method_exists($obj, $route['method'])) {    // if method of router found?

                    // check http variables for security.

                    if ( $re = $this->check_http_variables_existency() ) return error( $re['code'], $re['message'] );

                    // check http variables type for security
                    if ( $re = $this->validate_http_variables() ) return error( $re['code'], $re['message'] );

                    $method = $route['method'];
                    $obj->$method();
                } else {
                    $route = $this->get_current_route();
                    error(ERROR_MODEL_CLASS_METHOD_NOT_EXIST, "{$route['method']}() method does not exist on the route: $route[path]");
                }
            }
        } else {
            error(ERROR_MODEL_CLASS_NOT_FOUND, "model class - $route[path] - is not found");
        }

    }



}