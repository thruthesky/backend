<?php
/**
 *
 */

namespace model\system;
class System {


    public function run() {

        $route_name = in('route');
        if ( empty( $route_name ) ) error( ERROR_ROUTE_NOT_PROVIDED );

        $route = route()->getRoute( $route_name );
        route()->run( $route );

    }

}