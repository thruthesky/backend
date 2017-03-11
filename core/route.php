<?php



$route_files = rsearch( __MODEL_DIR__, '_route.php' );
//di($route_files);
$routes = [];
foreach (  $route_files as $file ) {
    include $file;
}

//di($routes);
