<?php

namespace model\meta;

class Meta_Proxy_Test {
    public function __construct()
    {

    }
    public function run() {

        $proxy = new Meta_Proxy( 'test-model', 1 );


        test( is_success( $proxy->set( 'a', 'b' ) ), "Proxy set 'a' to 'b' ");
        test( is_success( $proxy->set( 'c', 'd' ) ), "Proxy set 'c' to 'd' ");
        test( is_success( $proxy->set( 'e', 'f' ) ), "Proxy set 'e' to 'f' ");

        test( $proxy->get('a') == 'b', "Proxy a: {$proxy->get('a')}" );
        test( count($proxy->get()) == 3, "Proxy count should be 3" );

        test( is_success( $proxy->delete( 'a' ) ), "Proxy delete: a");
        test( count($proxy->get()) == 2, "Proxy count should be 2" );

        test( is_success( $proxy->delete() ), "Proxy delete all");
        test( count($proxy->get()) == 0, "Proxy count should be 0" );


    }
}

