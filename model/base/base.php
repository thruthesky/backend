<?php
/**
 *
 *
 *
 * @file base.php
 *
 * @desc base code for the system.
 *
 *
 *
 *
 *
 *
 *
 */
namespace model\base;
class Base {


    public function __construct()
    {

    }


    public function encryptPassword( $str ) {
        return password_hash( $str, PASSWORD_DEFAULT );
    }

    /**
     * Returns true if password matches.
     *
     * @param $plain_text_password
     * @param $encrypted_password
     * @return bool
     */
    public function checkPassword( $plain_text_password, $encrypted_password ) {
        return password_verify( $plain_text_password, $encrypted_password );
    }


}
