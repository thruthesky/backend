<?php

route()->add( 'hook.test', [
    'path' => '\\model\\hook\\hook_test',
    'method' => 'run',
    'validator' => function() {
        \model\test\Test::$reload = 1;
    }
]);