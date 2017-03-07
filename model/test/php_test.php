<?php

namespace model\test;

class Php_Test {
    public function __construct()
    {
    }
    public function run()
    {
        $user = user();
        test( $user instanceof \model\user\User, "User is User" );

        $admin = user('admin');
        test( $admin instanceof \model\taxonomy\Taxonomy, "User is a child of Taxonomy");
        test( $admin instanceof \model\entity\Entity, "User is a child of Entity");
        test( $admin instanceof \model\user\User, "And user is User");

    }
}

