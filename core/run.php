<?php



// http://localhost/www/backend-0.2/index.php?action=install

////// load routes
include __ROOT_DIR__ . '/core/route.php';


////// check route exists

$route_name = in('route');
if ( empty( $route_name ) ) error( ERROR_ROUTE_NOT_PROVIDED );
else {
    $route = get_route( $route_name );
    run_route( $route );
}


///// run the class method
function run_route( $route )
{

    if ( ! $route ) return error( ERROR_ROUTE_NOT_EXIST );
    set_current_route( $route );
    if (class_exists($route['path'])) {      // if class file ('model/model-name/class.php') exists?
        $obj = new $route['path']();                 // __constructor() runs.
        if (isset($route['method'])) { // if method is to be called?
            if (method_exists($obj, $route['method'])) {    // if method of router found?

                // check http variables for security.

                if ( $re = check_http_variables() ) return error( $re['code'], $re['message'] );

                // check http variables type for security
                if ( $re = check_http_variables_type() ) return error( $re['code'], $re['message'] );


                $obj->$route['method']();

            } else {
                $route = get_current_route();
                error(ERROR_MODEL_CLASS_METHOD_NOT_EXIST, "{$route['method']}() method does not exist on the route: $route[path]");
            }
        }
    } else {
        error(ERROR_MODEL_CLASS_NOT_FOUND, "model class - $route[path] - is not found");
    }

}



/**
 * @return array
 */
function get_keys_of_required_variables() {
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return [];
    $variables = $route['variables'];
    if ( ! isset( $variables['required'] ) ) return [];
    return $variables['required'];
}


/**
 * @return array
 */
function get_keys_of_optional_variables() {
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return [];
    $variables = $route['variables'];
    if ( ! isset( $variables['optional'] ) ) return [];
    return $variables['optional'];
}


/**
 * @return array
 */
function get_keys_of_system_variables() {
    $route = get_current_route();
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



function get_keys_of_variables() {


    /*
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return [];

    $variables = $route['variables'];


    $required = $optional = $system = [];
    if ( isset( $variables['required'] ) ) $required = $variables['required'];
    if ( isset( $variables['optional'] ) ) $optional = $variables['optional'];
    if ( isset( $variables['system'] ) ) $system = $variables['system'];

    $accepts = array_merge( $required, $optional, $system );

    */



    $all = array_merge(
        get_keys_of_required_variables(),
        get_keys_of_optional_variables(),
        get_keys_of_system_variables()
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
function check_http_variables() {

    if ( $required = get_keys_of_required_variables()) {
        foreach ( $required as $v ) {
            if ( array_key_exists( $v, $_REQUEST ) ) {

            }
            else {
                return [ 'code' => ERROR_REQUIRED_INPUT_IS_MISSING, 'message' => "required-input-is-missing $v" ];
            }
        }
    }

    $variables = get_keys_of_variables();
    foreach( array_keys( $_REQUEST ) as $key ) {
        if ( in_array( $key, $variables ) ) {

        }
        else {
            return [ 'code' => ERROR_INVALID_INPUT_VARIABLE, 'message' => "invalid-input-variable $key "];
        }
    }
    return OK;
}


/**
 * @return array|int
 *
 */
function check_http_variables_type() {

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
    }


    return OK;
}