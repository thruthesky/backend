<?php

$_table_name = DATABASE_PREFIX . 'user';


db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('id', 'varchar', 64)
    ->add('password', 'char', 255)
    ->add('session_id', 'varchar', 255)
    ->add('domain', 'varchar', 64)
    ->add('name', 'varchar', 64) // first name
    ->add('middle_name', 'varchar', 32)
    ->add('last_name', 'varchar', 64)
    ->add('nickname', 'varchar', 64)
    ->add('email', 'varchar', 64)
    ->add('gender', 'char', 1)
    ->add('birth_year', 'int')
    ->add('birth_month', 'int')
    ->add('birth_day', 'int')
    ->add('landline', 'varchar', 32)
    ->add('mobile', 'varchar', 32)
    ->add('address', 'varchar', 255)
    ->add('country', 'varchar', 255)
    ->add('province', 'varchar', 255)
    ->add('city', 'varchar', 255)
    ->add('zipcode', 'varchar', 32)
    // ->add('stamp_registration', 'INT UNSIGNED DEFAULT 0')

    ->add('block_begin_stamp', 'INT UNSIGNED DEFAULT 0') // time of blocked until.
    ->add('block_end_stamp', 'INT UNSIGNED DEFAULT 0') // time of blocked until.
    ->add('block_count', 'INT UNSIGNED DEFAULT 0') // time of blocked until.
    ->add('block_reason', 'varchar', 4096)

    ->add('resign_stamp', 'INT UNSIGNED DEFAULT 0') // time of resigned
    ->add('resign_reason', 'varchar', 1024)

    ->add('login_count', 'INT UNSIGNED DEFAULT 0')          // login count
    ->add('login_stamp', 'INT UNSIGNED DEFAULT 0')          // last login stamp
    ->add('login_ip', 'varchar', 128)             // last login ip.

    ->unique('id')
    ->index('session_id')
    ->index('domain')
    ->index('name')
    ->index('nickname')
    ->index('email')
    ->index('birth_year,birth_month,birth_day');



die_if_table_not_exist( $_table_name );


$admin_user_data = [
    'id' => ADMIN_ID,
    'password' => ADMIN_ID,
    'name' => ADMIN_ID
];
$re = user()->create( $admin_user_data );
if ( is_error( $re ) ) {
    die(" error: " . get_error_string($re));
}



$anonymous_user_data = [
    'id' => ANONYMOUS_ID,
    'password' => md5(time()),
    'name' => ANONYMOUS_ID
];
$re = user()->create( $anonymous_user_data );
if ( is_error( $re ) ) {
    die(" error: " . get_error_string($re));
}



$test_user_data = [
    'id' => TEST_USER_ID,
    'password' => md5(time()),
    'name' => TEST_USER_ID
];
$re = user()->create( $test_user_data );
if ( is_error( $re ) ) {
    die(" error: " . get_error_string($re));
}



$thruthesky_user_data = [
    'id' => 'thruthesky',
    'password' => md5(time()),
    'name' => 'thruthesky'
];
$re = user()->create( $thruthesky_user_data );
if ( is_error( $re ) ) {
    die(" error: " . get_error_string($re));
}
