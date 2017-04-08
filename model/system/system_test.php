<?php

namespace model\system;

class System_Test extends \model\test\Test {



    public function run()
    {


        if ( ! db()->tableExists( db()->tableName( 'user' ) ) ) {
            test( 0, "Backend is not installed: " . get_error_string(ERROR_BACKEND_NOT_INSTALLED));
            exit;
        }


    }

}
