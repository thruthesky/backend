<?php

namespace model\hook;
class Hook {

    public static $hooks;




    /**
     *
     * Adds hooks that will run after all route loading.
     *
     * @param $action
     * @param $variables
     * @param $func
     */
    public function add ( $action, $variables, $func = null ) {



        if ( empty( $action ) ) exit( error( -4100, 'hooks: action is empty') );

        // variable adjusting.
        if ( $func === null ) $func = $variables;

        if ( empty( $func ) ) exit( error( -4101, 'hooks: function is empty') );

        self::$hooks[ $action ][] = [
            'variables' => $variables,
            'function' => $func
        ];

    }


    public function get( $action = null ) {
        if ( $action ) {
            if ( isset( self::$hooks[ $action ] ) ) return self::$hooks[ $action ];
            else return [];
        }
        else return self::$hooks;
    }



    public function run( $action ) {


        $hooks = self::$hooks;

        if ( isset( $hooks[ $action ] ) && is_array($hooks[ $action ]) && $hooks[ $action ] ) {
            $hook_actions = $hooks[ $action ];
            foreach ( $hook_actions as $hook ) {
                $hook[ 'function' ]( $hook[ 'variables'] );
            }
        }

    }




}