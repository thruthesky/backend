<?php
/**
 * @see README.md
 */


namespace model\system;
class Install {
    public function __construct()
    {

        $installs = rsearch( __MODEL_DIR__, '_install.php' );
        foreach ( $installs as $install ) {
            include $install;
        }


        success( [ "Success." ] );


    }
}

