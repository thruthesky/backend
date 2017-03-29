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

        $this->loadInit();
        route()->loadRoutes();

        $route_name = in('route');
        if ( empty( $route_name ) ) return error( ERROR_ROUTE_NOT_PROVIDED );

        $route = route()->getRoute( $route_name );
        route()->run( $route );

        return OK;
    }



    private function loadInit() {
        $init_files = rsearch( __MODEL_DIR__, '_init.php' );
        foreach (  $init_files as $file ) {
            include $file;
        }
        hook()->run('after_init');
        return $this;
    }

    /**
     * @return \model\seo\SEO
     */
    public function seo() {
        $this->loadInit();
        return new \model\seo\SEO();
    }


}

