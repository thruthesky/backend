<?php
namespace model\hook;
class Hook_Test extends \model\test\Test
{
    public function run( ) {



        $hooks = hook()->get();
        $total_hooks = count($hooks);
        test( is_numeric( $total_hooks ) , "hook count: $total_hooks");



        $hooks = hook()->get( 'wrong hook' );
        test( count($hooks) == 0, "wrong hook get test");


        /**
         * How to HOOK. Run/Add a new kind of Hook and Variable test.
         */
        hook()->add( 'dew', ['a'=>'b'], function ( $vars ) {
            $add = $vars['add'];
            $run = $vars['run'];
            test( $add['a'] == 'b' && $run['c'] == 'd', "Hook variable OK.");
        });
        hook()->run( 'dew', ['c'=>'d'] );



        $hooks = hook()->get( 'dew' );
        test( count($hooks) == 1, "add hook: dew");


        $hooks = hook()->get();
        $new_total_hooks = count($hooks);


        test( $new_total_hooks == $total_hooks + 1, "hook total count: $new_total_hooks ");


        /**
         * How to use hook.
         */
        hook()->add( 'after_route', function () {
            $route = in('route');
            if ( $route != 'hook.test' ) return; // return if it's not your hook.

            test( true, "Do something for hook.test route");
        });





    }
}