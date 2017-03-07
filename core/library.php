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
function is_success( $code ) {
    return ! is_error( $code );
}