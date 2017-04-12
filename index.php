<?php
/**
 *
 * @file index.php
 * @note starter script
 *
 */


define('__ROOT_DIR__', __DIR__ );


include_once 'core/bootstrap.php';


// if ( DEBUG ) sleep(2);

backend()->run();

