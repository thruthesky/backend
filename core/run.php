<?php



// http://localhost/www/backend-0.2/index.php?action=install

////// load routes
include __ROOT_DIR__ . '/core/route.php';


////// check route exists

$route_name = in('route');
if ( empty( $route_name ) ) error( ERROR_ROUTE_NOT_PROVIDED );



$route = get_route( $route_name);
if ( ! $route ) error( ERROR_ROUTE_NOT_EXIST );

///// run the class method

    if ( class_exists( $route['path'] ) ) {      // if class file ('model/model-name/class.php') exists?

        $obj = new $route['path']();                 // __constructor() runs.

        if ( isset($route['method']) ) { // if method is to be called?
            if ( method_exists( $obj, $route['method'] ) ) {
                $obj->$route['method']();
            }
            else error( ERROR_MODEL_CLASS_METHOD_NOT_EXIST );
        }
    }
    else {
        error( ERROR_MODEL_CLASS_NOT_FOUND, "model class - $route[path] - is not found" );
    }

