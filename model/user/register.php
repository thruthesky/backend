<?php
/**
 *
 */
namespace model\user;
class Register {
    public function __construct()
    {


        $user['id'] = in('id');
        $user['password'] = in('password');
        $user['email'] = in('email');
        $user['nickname'] = in('nickname');
        $user['name'] = in('name');
        $user['middle_name'] = in('middle_name');
        $user['last_name'] = in('last_name');
        $user['domain'] = in('domain');
        $user['birth_day'] = in('birth_day');
        $user['birth_month'] = in('birth_month');
        $user['birth_year'] = in('birth_year');
        $user['gender'] = in('gender');
        $user['mobile'] = in('mobile');
        $user['landline'] = in('landline');
        $user['country'] = in('country');
        $user['city'] = in('city');
        $user['zipcode'] = in('zipcode');
        $user['province'] = in('province');
        $user['address'] = in('address');
        $user['meta'] = in('meta');


//        $session_id =;
        result( [user()->create( $user )] );


    }
}
