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

        $route_name = in('route');


        /// if NO Route ??
        if ( empty( $route_name ) ) {
            return backend()->seo()->patch()->render();

            /// return error( ERROR_ROUTE_NOT_PROVIDED ); /// @deprecated
        }

        route()->loadRoutes();

        $route = route()->getRoute( $route_name );
        route()->run( $route );

        return OK;
    }


    /**
     * @return $this
     * @see README.md#init
     */
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

