<?php
namespace model\system;
class System_Interface {

    public function version() {
        success(['version'=>"0.2"]);
    }

    public function error() {
        error( ERROR_FAKE_ERROR );
    }


    public function scriptError() {
        die( 1 / 0 );
    }
    public function internalServerError() {

        http_response_code(500);
        die();
    }

    public function timeoutError() {
        sleep(50);
        success(['note'=>'This should be timeout error'] );
    }

}