<?php

hook()->add('checkPassword', function( $variables ) {
    $run = $variables['run'];
    $plain_text_password = $encrypted_password = '';
    if ( isset($run['plain_text_password']) ) $plain_text_password = $run['plain_text_password'];
    if ( isset($run['encrypted_password']) ) $encrypted_password = $run['encrypted_password'];

    if ( empty($plain_text_password) || empty($encrypted_password) ) return null;
    if ( strlen( $encrypted_password) != 32 ) return null;

    return md5( $plain_text_password) == $encrypted_password;
});
