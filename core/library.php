<?php


/**
 *
 * Returns Truthy value if the input is error response.
 *
 * @param $code - is one of the responses
 *      1. HTTP call
 *      2. Fake Http call
 *      3. error number.
 *
 *
 *
 * @return bool
 */
function is_error ( $res ) {
    if ( is_numeric( $res ) && $res < 0 ) return $res;
    else if ( is_array( $res ) && isset( $res['code'] ) && $res['code'] < 0 ) return $res['code'];
    else return false;
}


/**
 *
 * @attention the input must be a response.
 *
 * @param $re
 * @return bool
 */
function is_success( $re ) {
    if ( is_array( $re ) && isset( $re['code'] ) && $re['code'] == 0 ) return TRUE;
    else return FALSE;
}