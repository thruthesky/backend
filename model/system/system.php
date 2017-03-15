<?php
/**
 *
 */

namespace model\system;
class System {


    /**
     *
     * run the route
     */
    public function run() {

        $route_name = in('route');
        if ( empty( $route_name ) ) error( ERROR_ROUTE_NOT_PROVIDED );

        $route = route()->getRoute( $route_name );
        route()->run( $route );

    }

}