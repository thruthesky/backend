<?php
/**
 *
 * @file helpers.php
 *
 * @desc Helper codes that are independent from the system.
 *
 *
 */

function di($o) {
    $re = print_r($o, true);
    $re = str_replace(" ", "&nbsp;", $re);
    $re = explode("\n", $re);
    echo implode("<br>", $re) . '<br>';
}


/**
 * @param $message
 *
 *
 *
 */
function debug_log( $message ) {
    global $DEBUG, $DEBUG_LOG_FILE_PATH;
    static $count_dog = 0;

    if ( ! $DEBUG ) return;
    if ( ! $DEBUG_LOG_FILE_PATH ) return;

    $count_dog ++;

    if( is_array( $message ) || is_object( $message ) ) {
        $message = print_r( $message, true );
    }
    else {

    }
    $message = "[$count_dog] $message\n";
    $fd = fopen( $DEBUG_LOG_FILE_PATH, 'a' );
    if ( $fd ) {
        fwrite( $fd, $message );
        fclose( $fd );
    }
}


/**
 *
 * @param $message
 *
 */
function debug_database_log( $message ) {
    global $DEBUG, $DEBUG_LOG_DATABASE;
    if ( ! $DEBUG ) return;
    if ( ! $DEBUG_LOG_DATABASE ) return;
    debug_log( $message );
}



function debug_print( $obj ) {
    global $DEBUG;
    if ( ! $DEBUG ) return;

    echo "<pre style='padding: 1em; background-color: lightgrey;'>
            <div style='font-size: 1.4em;'>DEBUG MESSAGE</div>
";
    print_r($obj);
    echo "</pre>";
}




function in ( $code, $default = null ) {
    if ( isset( $_REQUEST[ $code ] ) ) {
        if ( $_REQUEST[ $code ] ) return $_REQUEST[ $code ];
        else return $default;
    }
    else return $default;
}
















/**
 *
 * Saves JSON error info $system['error'];
 *
 * @attention this method does NOT exit the script. This is for mainly debug/unit test purpose.
 *
 * @note Only interfaces must use error() to echo JSON error string.
 *
 *
 *
 * @param $code
 * @param string $message
 *
 * @code
 *
 *      error('error-code');
 *      error('error-code', 'explanation message');
 *
 * @endcode
 * @return mixed - error code.
 *
 */
function error( $code, $message='' ) {
    global $em;
    if ( empty($message) && isset($em[ $code ]) ) $message = $em[ $code ];
    debug_log(" &gt;&gt;&gt; [ ERROR[ $code ] - \"$message\"");
    echo json_encode( ['code'=>$code, 'message'=>$message] );

}

function get_error_string( $re ) {
    global $em;
    if ( $re === FALSE ) {
        // di( debug_backtrace( ) );
        return "Error code is FALSE";
    }
    else if ( is_error( $re ) ) {
        if ( is_array( $re ) ) {
            $code = $re['code'];
            $message = $re['message'];
        }
        else {
            $code = $re;
            $message = $em[ $re ];
        }
        return " &gt;&gt;&gt; [ ERROR( $code ) - \"<b>$message</b>\" ] ";
    }
    else return null;
}

/**
 *
 *
 * Saves success data in $system['success']
 *
 * @note and later, it will be served by json_result()
 *
 * @attention when success json data printed out, it does not stop the script. Meaning the script will continue.
 *
 * @note Only interfaces must use error() to echo JSON error string.
 *
 * @param null $data - This must be null or array. Better to be associative array.
 *
 * @return $mixed
 */
function success( $data = null ) {
    if ( empty($data) || is_array( $data ) ) { }
    else error( ERROR_MALFORMED_RESPONSE );
    echo json_encode(['code'=>OK, 'data'=>$data]);

}

/**
 *
 * calls success() or error()
 *
 * @param $re - It gets error code defined in defines.php
 */
function result( $re ) {
    if ( isset($re['code'] ) && $re['code'] ) error( $re );
    else success( $re );
}




/**
 * Returns page no.
 *
 * @param $n
 * @return int
 */
function page_no( $n ) {
    if ( ! is_numeric( $n ) ) return 1;
    else if ( $n < 1 ) return 1;
    else return $n;
}

function page_item_limit( $n ) {
    global $DEFAULT_NO_OF_PAGE_ITEMS;
    if ( ! is_numeric( $n ) ) return $DEFAULT_NO_OF_PAGE_ITEMS;
    else if ( $n < 1 ) return $DEFAULT_NO_OF_PAGE_ITEMS;
    else return $n;
}


/**
 *
 * Search files based on $pattern and Return it.
 *
 * @param $folder
 * @param $pattern
 * @return array
 */
function rsearch($dir, $pattern) {
    $my_files = [];
    $tree = glob(rtrim($dir, '/') . '/*');
    if (is_array($tree)) {
        foreach($tree as $file) {
            if (is_dir($file)) {
                // echo $file . '<br/>';
                $_files = rsearch($file, $pattern);
                $my_files = array_merge( $my_files, $_files );
            } elseif (is_file($file)) {
                if ( strpos( $file, $pattern ) !== false ) $my_files[] = $file;
            }
        }
    }

    return $my_files;



}







function test( $re, $code ) {
    static $count = 0;
    $count ++;
    if ( is_array($re) ) {
        if ( isset($re['code']) && ! isset($re['idx']) ) { // server data.
            if ( $re['code'] ) test_error($re, $code, $count);
            else echo "<div class='success'>$count - SUCCESS: $code</div>";
        }
        else { // unknown data.
            if ( $re ) echo "<div class='success'>$count - SUCCESS: $code</div>";
            else test_error($re, $code, $count);
        }
    }
    else { //
        if ( $re ) echo "<div class='success'>$count - SUCCESS: $code</div>";
        else test_error($re, $code, $count);
    }
}

function test_error( $re, $code, $count ) {
    echo "<div class='error'>$count - ERROR : ( $re[code] $code ) - $re[message]</div>";
    echo "<pre>";
    debug_print_backtrace();
    echo "</pre>";
}
