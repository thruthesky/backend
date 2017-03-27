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
     * Returns sanitized post_data record to user.
     *
     * @note this method works on '$this'
     *
     * @param array $option
     * @return array
     */
    public function pre( $option = [] ) {
        $record = $this->getRecord();

        // delete security data.
        unset( $record['password'], $record['user_agent'] );




        //
        if ( $option && isset($option['extra']) && $option['extra'] ) {
            if ( isset($option['extra']['meta']) && $option['extra']['meta'] ) $record['meta'] = $this->meta()->get();
            if ( isset($option['extra']['file']) && $option['extra']['file'] ) $record['files'] = $this->file()->get();
        }
        else {
            $record['meta'] = $this->meta()->get();
            $record['files'] = $this->file()->get();

            //$record['files'] = (new File())->getRecords( " model='post' AND model_idx=$record[idx] ", 'idx, type, name');

        }


        // debug_log($record);
        return $record;
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
     * Returns an array of post after sanitizing.
     *
     * @param $records - array of post records.
     *
     * @param array $option
     * @return array
     */
    public function pres( & $records, $option = [] ) {
        $new_records = [];
        if ( empty( $records ) ) return $new_records;
        foreach( $records as $record ) {
            $new_records[] = post()->reset( $record )->pre( $option );
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

}
