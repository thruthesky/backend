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
 * @attention If you need to exit after printing out error use like below.
 * @code
 *          exit( error( -410, 'hooks: action is empty') );
 * @endcode
 *
 *
 * @note Only interfaces must use error() to echo JSON error string.
 *
 *
 *
 * @param $code
 *          if it is null, it treats as error array.
 *              $re = $route['validator']();
 *              if ( $re ) return error( $re );
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
 *
 *
 */
function error( $code, $message='' ) {
    global $em;
    if ( is_array( $code ) && isset($code['code']) && $code['code'] < 0 ) {
        $message = $code['message'];
        $code = $code['code'];
    }
    if ( empty($message) && isset($em[ $code ]) ) $message = $em[ $code ];
    if ( empty($code) && empty($message) ) {
        $code = ERROR_UNKNOWN;
        $message = $em[ $code ];
    }
                                        debug_log(" &gt;&gt;&gt; [ ERROR[ $code ] - \"$message\"");
    echo json_encode( ['code'=>$code, 'message'=>$message ] );
    return $code;
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
    //if ( isset($re['code'] ) && $re['code'] ) error( $re );
    if ( is_error( $re ) ) error( $re );
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


/**
 * Returns a url string that appears on the browser address bar.
 * @return string
 */
function current_url() {
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Returns a url string of the PHP script. ( before URL rewriting ).
 */
function current_script_url() {
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";
}



/**
 *
 * Cut UTF-8 string
 *
 * @param $str
 * @param $len
 * @param string $suffix
 * @return string
 */
function strcut($str, $len, $suffix="")
{
    $s = substr($str, 0, $len);
    $cnt = 0;
    for ($i=0; $i<strlen($s); $i++)
        if (ord($s[$i]) > 127)
            $cnt++;
    $s = substr($s, 0, $len - ($cnt % 3));
    if (strlen($s) >= strlen($str))
        $suffix = "";
    return $s . $suffix;
}
