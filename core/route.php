<?php



$route_files = rsearch( __MODEL_DIR__, '_route.php' );
//di($route_files);
$routes = [];
foreach (  $route_files as $file ) {
    include $file;
}

//di($routes);


/**
 * @param $name
 * @return mixed
 *      return FALSE if there is no route.
 */
function get_route( $name ) {
    global $routes;
    if ( isset( $routes[ $name ] ) && $routes[ $name ] ) return $routes[ $name ];
    else return FALSE;
}