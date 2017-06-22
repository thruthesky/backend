<?php
namespace model\convert;
class Convert extends \model\entity\Entity {
    public function run() {
            $this->convertTalktativeMember();
            $this->convertTalkativeQna();
            $this->convertTalkativeReview();
    }


    public function convertPOSTDATA( $data, $old_post_config ) {

        $config_idx = $this->postConfigCheckDeleteCreate( $data );

        $rows = db()->rows("SELECT idx,idx_root,idx_parent,member_id,subject,content,password,stamp,post_id FROM post_data WHERE post_id LIKE '$old_post_config' AND idx_root = 0 AND idx_parent = 0 ");
        $count = 0;

        foreach ( $rows as $row ) {

            post( $row['idx_root'] )->delete();

            $data = null;
//
//            $data['root_idx'] = $row['idx_root'];
//            $data['parent_idx'] = $row['idx_parent'];
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

                $user = user($row['member_id']);
                post( $post_idx )->update([
                    "password" => $row['password'],
                    "user_idx" => $user->idx,
                    "created"  => $row['stamp'],
                    "name" => $user->name,
                    "email" => $user->email
                ]);

                $this->getPostDataParentComment( $row['idx'], $post_idx, $config_idx, $old_post_config  );

                $count++;
                echo "POST::$count : $user->idx  |  name : $user->name \n" ;
            }
        }

    }



    public function getPostDataParentComment( $old_parent_idx, $new_parent_idx, $config_idx, $old_post_config  ) {
        $rows = db()->rows("SELECT idx,idx_root,idx_parent,member_id,subject,content,password,stamp,post_id FROM post_data WHERE post_id LIKE '$old_post_config' AND idx_root = $old_parent_idx ");
        $count = 0;

        foreach ( $rows as $row ) {

            post( $row['idx_root'] )->delete();

            $data = null;

            $data['root_idx'] = $new_parent_idx;
            $data['parent_idx'] = $new_parent_idx;
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

                $user = user($row['member_id']);
                post( $post_idx )->update([
                    "password" => $row['password'],
                    "user_idx" => $user->idx,
                    "created"  => $row['stamp'],
                    "name" => $user->name,
                    "email" => $user->email
                ]);
                $count++;
                echo "Comment::$count : $user->idx  |  name : $user->name \n" ;
            }
        }
    }


    /**
     * CONVERTING POSTDATA OF QNA
     */

    public function convertTalkativeQna() {
        echo "\n convertTalkativePostQna:: Start \n";

        $data = [
            'id' => 'qna',
            'name' => 'Q&A',
            'description' => 'Question and Answer'
        ];

        $this->convertPOSTDATA( $data, 'qna' );

    }

    /**
     * CONVERTING POSTDATA OF REVIEW
     */
    public function convertTalkativeReview() {
        echo "\n convertTalkativePostReview:: Start \n";

        $data = [
            'id' => 'review',
            'name' => 'Student Review',
            'description' => 'Student Testimonial'
        ];
        $this->convertPOSTDATA( $data, 'postscript' );
    }

    /**
     * CONVERTING MEMBER TO BACKEND USERS
     */

    public function convertTalktativeMember() {

        $rows = db()->rows("select idx,domain,id,password,name,nickname,landline,mobile,email,birthday,gender,address,zipcode,login_count,login_stamp,login_ip from member");
        $count = 0;

        foreach ( $rows as $row ) {

            if( $row['id'] == 'admin' || $row['id'] == 'thruthesky' ) continue;


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

                user( $session_id )->update(['password' => $row['password']]);

                $count++;
                echo $count;
            }

        }
        echo "\nTotal:: " . $count;
    }


    /**
     * GET POST_CONFIG_IDX
     */
    public function postConfigCheckDeleteCreate( $data ){
        $admin_session_id = user(ADMIN_ID )->getSessionId();
        echo "\n ADMIN SESSION" . $admin_session_id . "\n";

        if ( config( $data['id'] )->exist() ) {
            $config_idx = config( $data['id'] )->idx;
        } else {
            $config_idx = config()->create( $data );
        }

        echo "\n Forum ID:: " . $config_idx . "\n";

        return $config_idx;
    }


}