<?php



// http://localhost/www/backend-0.2/index.php?action=install

////// load routes
include __ROOT_DIR__ . '/core/route.php';


////// check route exists

$route_name = in('route');
if ( empty( $route_name ) ) error( ERROR_ROUTE_NOT_PROVIDED );
else {
    $route = get_route( $route_name);
    run_route( $route );
}


///// run the class method
function run_route( $route )
{

    if ( ! $route ) error( ERROR_ROUTE_NOT_EXIST );
    set_current_route( $route );
    if (class_exists($route['path'])) {      // if class file ('model/model-name/class.php') exists?
        $obj = new $route['path']();                 // __constructor() runs.
        if (isset($route['method'])) { // if method is to be called?
            if (method_exists($obj, $route['method'])) {    // if method of router found?

                $re = check_http_variables();       // check input data for security.
                if ( $re === TRUE ) {           // variables OK?
                    $obj->$route['method']();
                }
                else error( $re['code'], $re['message'] );
            } else {
                $route = get_current_route();
                error(ERROR_MODEL_CLASS_METHOD_NOT_EXIST, "{$route['method']}() method does not exist on the route: $route[path]");
            }
        }
    } else {
        error(ERROR_MODEL_CLASS_NOT_FOUND, "model class - $route[path] - is not found");
    }

}

function check_http_variables() {
    $route = get_current_route();
    if ( ! isset( $route['variables'] ) ) return TRUE;
    $variables = $route['variables'];


    $required = $optional = $system = [];
    if ( isset( $variables['required'] ) ) $required = $variables['required'];
    if ( isset( $variables['optional'] ) ) $optional = $variables['optional'];
    if ( isset( $variables['system'] ) ) $system = $variables['system'];

    //debug_log("required:");
    //debug_log($optional);

    $accepts = array_merge( $required, $optional, $system );





    //debug_log( $_REQUEST );
    //debug_log( $variables );
    //debug_log("accepts: ");
    //debug_log( $accepts );



    if ( $required ) {
        foreach ( $required as $v ) {
            if ( array_key_exists( $v, $_REQUEST ) ) {

            }
            else {
                return [ 'code' => ERROR_REQUIRED_INPUT_IS_MISSING, 'message' => "required-input-is-missing $v" ];
            }
        }
    }

    foreach( array_keys( $_REQUEST ) as $key ) {
        if ( in_array( $key, $accepts ) ) {

        }
        else {
            return [ 'code' => ERROR_INVALID_INPUT_VARIABLE, 'message' => "invalid-input-variable $key "];
        }
    }
    return TRUE;
}