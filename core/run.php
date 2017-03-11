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
  
                $method = $route['method'];
                $obj->$method();
            } else {
                $route = get_current_route();
                error(ERROR_MODEL_CLASS_METHOD_NOT_EXIST, "{$route['method']}() method does not exist on the route: $route[path]");
            }
        }
    } else {
        error(ERROR_MODEL_CLASS_NOT_FOUND, "model class - $route[path] - is not found");
    }

}

