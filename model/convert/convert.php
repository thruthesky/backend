<?php
namespace model\convert;
class Convert extends \model\entity\Entity {
    public function run() {

        $rows = db()->rows("select idx,domain,id,password,name,nickname,landline,mobile,email,birthday,gender,address,zipcode,login_count,login_stamp,login_ip from member");
        //print_r($rows);
        //print_r($rows[0]);
        $count = 0;
        foreach ( $rows as $row ) {
            $data = null;

            $data['id'] = $row['id'] ? $row['id'] : null;
            $data['domain'] = $row['domain'] ? $row['domain'] : null;
            $data['password'] = $row['password'] ? $row['password'] : null;
            $data['name'] = $row['name'] ? $row['name'] : null ;
            $data['nickname'] = $row['nickname'] ? $row['nickname'] : null;
            $data['landline'] = $row['landline'] ? $row['landline'] : null;
            $data['mobile'] = $row['mobile'] ? $row['mobile'] : null;
            $data['email'] = $row['email'] ? $row['email'] : null;
            $data['gender'] = $row['gender'] ? $row['gender'] : null;
            $data['address'] = $row['address'] ? $row['address'] : null;
            $data['zipcode'] = $row['zipcode'] ? $row['zipcode'] : null;
            $data['login_count'] = $row['login_count'] ? $row['login_count'] : null;
            $data['login_stamp'] = $row['login_stamp'] ? $row['login_stamp'] : null;
            $data['login_ip'] = $row['login_ip'] ? $row['login_ip'] : null;

            if( $row['birthday'] ) {
                $data['birth_year'] = substr($row['birthday'], 0, 4);
                $data['birth_month'] = substr($row['birthday'], 4, 2);
                $data['birth_day'] = substr($row['birthday'], 6, 2);
            }

            $session_id = user()->create( $data );

            if ( is_error( $session_id ) ) {
                echo "\n" . $data['id'] . " error :: ";
                error( $session_id );
            }
            else {
                echo "\n" . $data['id'] . " => " . $session_id;
                $count++;
            }

        }
        echo "\n Total::" . $count;

    }

}