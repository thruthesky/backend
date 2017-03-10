<?php
namespace model\post;
use model\test\Test;

class Post_Config_Test extends Test {

    public function run() {
        $this->create();
    }
     public function create() {
        $id = "config-create-test-1";
        $data['id'] = $id;
        $data['name'] = "name" . $id;
        $data['description'] = "description-" . $id;
        $re = $this->route("post_config.create", $data );
        test( is_error( $re ), "Post Config Create: " . get_error_string( $re ) );
     }
}