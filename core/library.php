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
 * Returns true if the $re is a susccess.
 *
 * @note if $re['code'] has number less than 0, then it returns FALSE.
 * @note if $re is number and it is less than 0, then it returns FALSE.
 *
 *
 *
 * @attention the input can be a response or a primitive value.
 *
 * @param $re
 * @return bool
 */
function is_success( $re ) {
    if ( is_array( $re ) ) {
        //di("yes array");
        if ( isset( $re['code'] ) ) {
            //di("yes code is set");
            if ( $re['code'] == 0 ) {       // if code is 0.
                //di("yes code is 0");
                return TRUE;
            }
            else return FALSE;              // if code is NOT 0.
        }
    }

    if ( is_numeric( $re ) && $re < 0 ) return FALSE;


    return TRUE;
}