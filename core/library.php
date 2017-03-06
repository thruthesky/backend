<?php



function is_error ( $code ) {
    if ( is_numeric( $code ) && $code < 0 ) return true;
    else return false;
}