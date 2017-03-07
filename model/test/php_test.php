<?php

namespace model\test;

class Php_Test {
    public function __construct()
    {
    }
    public function run()
    {
        $meta = meta();
        test( $meta instanceof \model\meta\Meta, "Meta is Meta" );
    }
}

