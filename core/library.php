<?php


/**
 *
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
function is_error ( $code ) {
    if ( is_numeric( $code ) && $code < 0 ) return true;
    else if ( is_array( $code ) && isset( $code['code'] ) && $code['code'] < 0 ) return true;
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