<?php
namespace model\convert;
class Convert extends \model\entity\Entity {
    public function run() {
            $this->convertTalktativeMember();
            $this->convertTalkativeQna();
            $this->convertTalkativeReview();
//        $this->convertTalkativePost();
    }

    public function convertTalkativePost(){
        $config_id = 'review';

        $data = [
            'id' => 'qna',
            'name' => 'Q&A',
            'description' => 'Question and Answer'
        ];

        echo "SELECT idx_root,idx_parent,member_id,subject,content,password,stamp FROM post_data WHERE post_id = '$data[id]' ";

        $admin_session_id = user(ADMIN_ID)->getSessionId();
        echo "\n " . $admin_session_id . "\n";

        if ( config( $config_id )->exist() ) {
            $config_idx = config( $config_id )->idx;
            //print_r($config_idx);
            echo " Forum ID:: " . $config_idx . "\n";
        }
        else {
            $config_idx = config()->create( $config_id );
            echo "\n Forum ID:: " . $config_idx . "\n";
        }

    }






    public function convertTalktativeMember() {
        $rows = db()->rows("select idx,domain,id,password,name,nickname,landline,mobile,email,birthday,gender,address,zipcode,login_count,login_stamp,login_ip from member");
        //print_r($rows);
        //print_r($rows[0]);
        $count = 0;
        foreach ( $rows as $row ) {

            if( $row['id'] == 'admin' || $row['id'] == 'thruthesky' ) continue;

            //if ( user()->load( $row['id'] )->exist() ) user()->load( $row['id'] )->delete();
            if ( user( $row['id'] )->exist() ) user( $row['id'] )->delete();

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
                echo "\n";
            }
            else {
                //user()->load( $session_id )->update(['password' => $row['password']]);
                user( $session_id )->update(['password' => $row['password']]);
                //echo "\n" . $data['id'] . " => " . $session_id;
                $count++;
                echo $count;
            }

        }
        echo "\nTotal:: " . $count;
    }

    public function postConfigCheckDeleteCreate( $data ){

        //$admin_session_id = route("login", ['id'=>ADMIN_ID, 'password'=>ADMIN_ID]);
        $admin_session_id = user(ADMIN_ID)->getSessionId();
        echo "\n " . $admin_session_id . "\n";

        if ( config( $data->id )->exist() ) config( $data->id )->delete();


        $config_idx = config()->create( $data );

        echo "\n Forum ID:: " . $config_idx . "\n";

        return $config_idx;
    }

    public function convertTalkativeQna() {
        echo "\n convertTalkativePostQna:: Start \n";

        $data = [
            'id' => 'qna',
            'name' => 'Q&A',
            'description' => 'Question and Answer'
        ];

        $config_idx = $this->postConfigCheckDeleteCreate( $data );

        $rows = db()->rows("SELECT idx_root,idx_parent,member_id,subject,content,password,stamp FROM post_data WHERE post_id LIKE 'qna' ");
        $count = 0;

        foreach ( $rows as $row ) {

            post( $row['idx_root'] )->delete();

            $data = null;

            $data['root_idx'] = $row['idx_root'];
            $data['parent_idx'] = $row['idx_parent'];
            $data['post_config_idx'] = $config_idx;
            $data['title']  = $row['subject'];
            $data['content'] = $row['content'];

            $post_idx = post()->create($data);

            if ( is_error( $post_idx ) ) {
                echo "\n" . $data['id'] . " error :: ";
                error( $post_idx );
                echo "\n";
            }
            else {
                //user()->load( $session_id )->update(['password' => $row['password']]);
                //echo "\n MEMBER INFO:: \n";
                //print_r(user($row['member_id']));

                $user = user($row['member_id']);
                post( $post_idx )->update([
                    "password" => $row['password'],
                    "user_idx" => $user->idx,
                    "created"  => $row['stamp'],
                    "name" => $user->name,
                    "email" => $user->email
                ]);

                //echo "\n" . $data['id'] . " => " . $session_id;
                $count++;
                echo "$count : $user->idx  |  name : $user->name \n" ;
            }
        }
    }


    public function convertTalkativeReview() {
        echo "\n convertTalkativePostReview:: Start \n";

        $data = [
            'id' => 'review',
            'name' => 'Student Review',
            'description' => 'Student Testimonial'
        ];

        $config_idx = $this->postConfigCheckDeleteCreate( $data );

        $rows = db()->rows("SELECT idx_root,idx_parent,member_id,subject,content,password,stamp FROM post_data WHERE post_id LIKE 'postscript' ");
        $count = 0;

        foreach ( $rows as $row ) {

            post( $row['idx_root'] )->delete();

            $data = null;

            $data['root_idx'] = $row['idx_root'];
            $data['parent_idx'] = $row['idx_parent'];
            $data['post_config_idx'] = $config_idx;
            $data['title']  = $row['subject'];
            $data['content'] = $row['content'];

            $post_idx = post()->create($data);

            if ( is_error( $post_idx ) ) {
                echo "\n" . $data['id'] . " error :: ";
                error( $post_idx );
                echo "\n";
            }
            else {
                //user()->load( $session_id )->update(['password' => $row['password']]);
                //echo "\n MEMBER INFO:: \n";
                //print_r(user($row['member_id']));

                $user = user($row['member_id']);
                post( $post_idx )->update([
                    "password" => $row['password'],
                    "user_idx" => $user->idx,
                    "created"  => $row['stamp'],
                    "name" => $user->name,
                    "email" => $user->email
                ]);

                //echo "\n" . $data['id'] . " => " . $session_id;
                $count++;
                echo "$count : $user->idx  |  name : $user->name \n" ;
            }
        }
    }

}