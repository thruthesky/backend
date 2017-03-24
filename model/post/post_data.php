<?php
/**
 * @see README.md
 */
namespace model\post;
use model\file\File;

class Post_Data extends Post {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('post_data');
    }


    /**
     *
     *
     * Return TRUE if the password matches.
     *
     *
     * @Overrides entity()checkPassword()
     *
     * @param $plain_text_password
     * @param $_
     * @return bool
     */
    public function checkPassword($plain_text_password, $_ = null )
    {
        return parent::checkPassword($plain_text_password, $this->password);
    }


    /**
     *
     * This does not delete the record. but set it empty.
     *
     *
     * Overrides the entity()->delete() NOT to delete the record but to SET empty title and content.
     * @warning This does not delete the record.
     *
     * @return bool|number - same as entity()->delete()
     *
     */
    public function delete() {
        $record_copy = $this->getRecord();
        $re = $this->update([
            'deleted' => '1',
            'title' => '',
            'content' => ''
        ]);
        if ( is_success($re) ) {
            db()->insert( table_post_deleted(), $record_copy );
        }
        $this->deleteCache();

        return $re;
    }


    /**
     *
     * Returns sanitized post_data record to user.
     *
     * @return array
     */
    public function pre() {
        $record = $this->getRecord();
        unset( $record['password'], $record['user_agent'] );
        $record['meta'] = meta()->get( $this->getTable(), $record['idx']);


        $record['files'] = (new File())->getRecords( " model='post' AND model_idx=$record[idx] ", 'idx, type, name');


        // debug_log($record);
        return $record;
    }


    /**
     *
     * Returns an array of post after sanitizing.
     *
     * @param $records - array of post records.
     *
     * @return array
     *
     */
    public function pres( & $records ) {
        $new_records = [];
        if ( empty( $records ) ) return $new_records;
        foreach( $records as $record ) {
            $new_records[] = post()->reset( $record )->pre();
        }
        return $new_records;
    }

    public function getConfigs( & $records ) {
        if ( empty( $records ) ) return [];

        $configs = [];
        foreach( $records as $post ) {
            if ( isset($post['post_config_idx']) ) {
                $config = config( $post['post_config_idx'] );
                if ( $config->exist() ) $configs[ $config->id ] = $config->getRecord();
            }
        }
        return $configs;

    }


    public function deleted() {
        return $this->deleted;
    }


    /**
     * Return 'OK' if the permission OK.
     *
     * @return int
     */
    public function editPermission() {

        if ( currentUser()->isAdmin() ) return OK;
        else if ( currentUser()->isAnonymous() ) {
            if ( empty( in('password') ) ) return ERROR_PASSWORD_EMPTY;
            if ( $this->checkPassword( in('password') ) ) return OK;
            else return ERROR_WRONG_PASSWORD;
        }
        else if ( currentUser()->idx == $this->user_idx ) return OK;
        else return ERROR_NOT_YOUR_POST_DATA;

    }

    public function deletePermission() {



        if ( currentUser()->isAdmin() ) return OK;



        if ( currentUser()->isAnonymous() ) {

            if ( empty( in('password') ) ) return ERROR_PASSWORD_EMPTY;

            if ( $this->user_idx != currentUser()->idx ) return ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS;
            if ( $this->checkPassword( in('password') ) ) return OK;
            else return ERROR_WRONG_PASSWORD;

        }

        if ( $this->user_idx == currentUser()->idx ) return OK;

        return ERROR_NOT_YOUR_POST_DATA;

    }

}
