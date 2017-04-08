<?php
/**
 * @see README.md
 */


namespace model\system;
class Install {
    public function __construct()
    {

        if ( DEBUG ) {
            // if debug, then just overwrite the databases.
        }
        else {
            // if it's debug, it cannot overwrite old database tables.
            if ( db()->tableExists( db()->tableName( 'user' ) ) ) {
                return error( ERROR_ALREADY_INSTALLED, "Backend is already installed. If you want to install again, delete the tables or change database prefix." );
            }
        }


        $installs = rsearch( __MODEL_DIR__, '_install.php' );
        foreach ( $installs as $install ) {
            include $install;
        }


        success( [] );


    }
}

