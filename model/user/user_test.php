<?php

namespace model\user;
class User_Test {


    public function run() {


        $this->cache();

        $countAll = user()->countAll();


    }


    public function cache() {
        
        $admin = user('admin');
        $firstCount = $admin->getResetCount( 'admin' );

        $admin = user('admin');
        $secondCount = $admin->getResetCount( 'admin' );

        test( $firstCount == ($secondCount-1), "First cache count test");

        $admin = user('admin');
        $thirdCount = $admin->getResetCount( 'admin' );

        test( $firstCount == ($thirdCount-2), "Second cache count test");
    }

}