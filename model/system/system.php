<?php
/**
 *
 */

namespace model\system;
class System {


    /**
     *
     * run the route
     * @return mixed
     */
    public final function run() {

        route()->loadRoutes();

        $route_name = in('route');
        if ( empty( $route_name ) ) return error( ERROR_ROUTE_NOT_PROVIDED );

        $route = route()->getRoute( $route_name );
        route()->run( $route );

        return OK;
    }

}

