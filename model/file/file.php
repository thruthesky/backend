<?php

namespace model\file;
class File extends \model\entity\Entity
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('file');
    }


    /**
     *
     *
     * @important When you upload/save a file, you CAN save 'model', 'mode_idx', 'code' BUT these might be replaced on hooking.
     *  Especially, the 'model_idx' will mostly be changed since you don't know what model_idx is if you don't create the entity.
     *  You can think of 'model_idx' as entity idx since model idx is really mean entity idx and how will you know an entity idx when it is not actually created and exists
     *  Of course, there is a way to know it. You create entity first and upload photo later. If you want, you can do it. may be there might some occasion to do it.
     *  For instance, you want users to update their photos, you have entity idx already of the user. user.idx is entity idx. User can edit post and update/change photos. And the entity idx is the post.idx and you know it already.
     *
     *  So, inputting model, model_idx, code is not really mandatory and not important.
     *
     *  BUT, ONCE, you fill/input "model, model_idx, code", these values will NOT be changed while hooking with file::hookUpload().
     *
     *  IF you leave these variables EMPTY while uploading files, then these will be FILLED/changed according to the entity that you are going to create in file::hookUoload().
     *      If the user is registering, then the model will be user, model_idx will be the user.idx, and code maybe set to 'primary-photo' depending on the registration interface.
     *      Normally You leave these variables when you upload files before creating entity like registration, creating new post, new comment, etc.
     *
     *
     * @note If the 'file.user_idx' is anonymous idx, then it will be changed to the currentUser()'s idx while hooking.
     * @note If the 'file.model' is 'user', then 'file.model_idx' and 'file.user_idx' maybe the same since the model of the entity is owned by the same user whose user.idx is just the same as model_idx(but there might a change that model_idx and user_idx may differ)
     *
     *
     * @warning Make sure to send `model_idx` when upload to 'replace' a file or when upload files on entity edit.
     * @attention Missing `model_idx` will cause `model_idx` to be 0 Unless you hook it.
     * @attention When anonymous user uploads a primary photo, it MUST NOT be unique because there are many anonymous. If you set unique, then backend will delete all the photos of anonymous.
     *              - But for changing primary photo on user profile, 'UNIQUE' must be 'true' and `model_idx` MUST BE set to user.idx
     *
     * @param $fileinfo - holds all information about saving files like model, model_idx, code, etc.
     * @param $userfile
     * @return array|int|number
     */
    public function save( $fileinfo, $userfile )
    {
        debug_log($fileinfo);

        /////////////// Prepare variables

        if ( isset($fileinfo['model']) ) $model = $fileinfo['model'];
        else $model = ''; // return ERROR_MODEL_IS_EMPTY;

        if ( isset($fileinfo['model_idx']) ) $model_idx = $fileinfo['model_idx'];
        else $model_idx = 0;

        $code = isset($fileinfo['code']) ? $fileinfo['code'] : null;

        if ( ! isset($userfile['error'])  ) return ERROR_UPLOAD_ERROR_NOT_SET;
        //debug_log($userfile);


        if ( $userfile['error'] === UPLOAD_ERR_OK && $userfile['size'] && $userfile['tmp_name'] ) {
            // uploading from web-browser to web-server was successfully done.
            // continue your work.
        }
        else {
            return $this->errorResponse( $userfile['error'] );
        }

        $unique = 'N';
        if ( isset($fileinfo['unique']) ) $unique = $fileinfo['unique'];

        $finish = 'N';
        if ( isset($fileinfo['finish']) ) $finish = $fileinfo['finish'];

        ///////////// End of Prepare Variables




        // delete old files that are not hooked.
        $this->deleteUnhooked();


        $src = $userfile['tmp_name'];
        if ( ! file_exists( $src ) ) return ERROR_UPLOAD_FILE_NOT_EXIST;


        /**
         * If unique option is set, delete previously uploaded files.
         */
        if ( $unique == 'Y' ) {
            debug_log("File::save() Going to delete unique of : $model, $model_idx, $code");


            /**
             * @fix for SQLite, last ROWID on autoincrement wll be reused if last has been deleted.
             *      When a user uploads a unique file, then the newly created ROWID(file.idx) may remain the same as last ROWID deleted and newly created ROWID will have the deleted ROWID. and it would cause error on file uploads since file uploads and its URL/SRC is based on file.idx
             *      so, it created temporary idx and this will be deleted on garbage collection.
             *
             */

            if ( DATABASE_TYPE == 'sqlite' ) $temp_idx = $this->reset( ['finish' => 'N' ] )->create();

            $re = $this->deleteBy( $model, $model_idx, $code );
            if ( is_error($re) ) {
                debug_log("File::deleteBy($model, $model_idx, $code) error: $re : " . get_error_string($re));
                return $re;
            }
        }


        //

        $idx = $this->reset( [] )
            ->set('name',$userfile['name'])
            ->set('size', $userfile['size'])
            ->set('type',$userfile['type'])
            ->set('model', $model )
            ->set('model_idx', $model_idx )
            ->set('code', $code )
            ->set('user_idx', currentUser()->idx )
            ->set('finish', $finish)
            ->create();

        if ( is_error($idx) ) return ERROR_FILE_UPLOAD_CREATE_IDX_FAILED;



        $dst = DIR_UPLOAD . "/$idx";
        debug_log("move_uploaded_file($src, $dst)");

        if ( file_exists( $dst ) ) return [ 'code' => ERROR_UPLOAD_FILE_EXIST, 'message' => "the file.idx ($idx) of uploaded-file already exists. This error often causes when the database reset, but the files were not removed. so, when a file is uploaded, idx begins with 1. but the file of idx 1 is already exists." ];
        if ( is_test() ) $re = @copy( $src, $dst );     // This is only used when unit testing.
        else $re = @move_uploaded_file( $src, $dst );

        if( ! $re ) {
            $error = error_get_last();
            f()->load($idx)->delete();
            return [ 'code' => ERROR_MOVE_UPLOADED_FILE, 'message' => $error['message'] ];
        }

        debug_log("file uploaded: idx: $idx");

        return $idx;
    }

    private function errorResponse($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $code = ERROR_UPLOAD_ERR_INI_SIZE;
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $code = ERROR_UPLOAD_ERR_FORM_SIZE;
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $code = ERROR_UPLOAD_ERR_PARTIAL;
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $code = ERROR_UPLOAD_ERR_NO_FILE;
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $code = ERROR_UPLOAD_ERR_NO_TMP_DIR;
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $code = ERROR_UPLOAD_ERR_CANT_WRITE;
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $code = ERROR_UPLOAD_ERR_EXTENSION;
                $message = "File upload stopped by extension";
                break;

            default:
                $code = ERROR_UNKNOWN;
                $message = "Unknown upload error";
                break;
        }
        return [ 'code' => $code, 'message' => $message ];
    }


    private function deleteUnhooked() {

        $files = $this->getOldUnhookedList();
        if ( $files ) {
            debug_log(" ------------ Going to delete OLD files -----------");
            foreach ( $files as $file ) {
                debug_log("old file: $file[idx]");
                self::delete( $file['idx'] );
            }
        }

    }

    /**
     * @note if parameter idx is empty then it will delete the file of the object
     *
     * @attention it overrides entity()->delete().
     * @param null $idx - file.idx to delete
     * @return number
     *
     *          OK - on success.
     *          ERROR_CODE - on error
     *
     *
     *
     *
     */
    public function delete( $idx = null ) {

        if ( $idx !== null ) {
            if ( empty( $idx ) ) {     // $idx was passed but no value.
                return ERROR_IDX_EMPTY_ON_FILE_DELETE;
            }
            else {
                if( $idx ) $this->reset($idx);  // if a file idx passed over argument.
            }
        }



        if ( ! $this->exist() ) {
            debug_log("File::delete( $idx ) with this->idx={$this->idx} is NOT Exists");
            return ERROR_FILE_NOT_EXIST;
        }

        debug_log("Going to File::delete() : " . $this->idx);
        debug_log("file: {$this->idx} exists in database.");


        $this->debug_log();
                // entity()->load($idx)->delete();
        $file_path = $this->path( $this->idx );
        if ( file_exists($file_path) ) {
            debug_log("file: {$this->idx} exists in hdd.");
            @unlink( $file_path );
            if ( file_exists( $file_path ) ) {
                debug_log("ERROR: file: {$this->idx} still exists after unlink().");
                return ERROR_FILE_UNLINK_FAILED;
            }
            else debug_log("Unlink() success : file: {$this->idx} unlink() success. The file has been deleted fron HDD.");
        }
        else {
            // @warning if it runs here, It is an error. it tries to delete file that does not exists.
            // return ERROR_UPLOAD_FILE_EXIST;
            debug_log("file NOT exist for delete: $file_path");
        }
        $re = parent::delete();
        if ( is_error($re) ) return $re;

        debug_log("Success on File::delete(). file: $file_path deleted from DB and HDD.");
        return OK;

    }


    /**
     * this method deletes the file base on model, model_idx and code
     * @note it uses $this->delete();
     *
     *
     * @param $model
     * @param $model_idx
     * @param null $code
     *
     * @note If $code is null, then it will delete all the files of that model and model_idx.
     * @note model, model_idx, code are not unique which means that might be more than one file which has same model, model_idx and code.
     *      This method will delete all the files that matches on input $model, $model_idx and $code.
     *
     * @return number
     *
     *      OK - on success
     *      ERROR CODE - on error. Returns error if any of the multi-files to delete case an error.
     *
     */
    public function deleteBy( $model, $model_idx, $code=null ) {
        debug_log("Going to delete: file::deleteBy( $model, $model_idx, $code )");
        if ( $code ) $and_code = "AND code='$code'";
        else $and_code = null;
        $files = db()->rows("SELECT idx FROM {$this->getTable()} WHERE model='$model' AND model_idx=$model_idx $and_code");
        if ( is_error($files) ) return $files;
        debug_log("Found " . count($files) . " files to delete.");
        if ( $files ) {
            foreach ( $files as $file ) {
                $re = self::delete( $file['idx'] );
                if ( is_error($re) ) {
                    debug_log("ERROR: File::deleteBy() deleting $file[idx] : " . get_error_string($re));
                    return $re;
                }
            }
        }

        return OK;
    }

    public function path( $idx = null ) {
        if ( $idx === null ) $idx = $this->idx;
        return DIR_UPLOAD . "/$idx";

    }

    private function getOldUnhookedList( $no = 100 ) {
        $time = time() - TIME_TO_DELETED_OLD_UNHOOKED_FILE;
        return db()->rows("SELECT idx FROM {$this->getTable()} WHERE finish = 'N' AND created < $time LIMIT $no");

    }

    public function count( $model, $model_idx = 0, $code =NULL  )
    {
        if( $code ) $and_code = "AND code = '$code'";
        else $and_code = NULL;
        return parent::count("model = '$model' AND model_idx = $model_idx $and_code");
    }

    /**
     *
     * @see \model\file\File::save()
     *
     * @param $model
     * @param $model_idx
     * @param $code
     * @param $file_idxes
     * @return int
     */
    public function hook($model, $model_idx, $code, $file_idxes)
    {

        //debug_log("File::hook( $model, $model_idx, $code )");
        //debug_log( $file_idxes );


        if ( empty( $file_idxes ) ) return OK;
        if ( ! is_array( $file_idxes ) ) return ERROR_HOOK_FILE_IDX_IS_NOT_IN_ARRAY;
        foreach ( $file_idxes as $idx ) {
            if ( empty($idx) || ! is_numeric($idx) ) return ERROR_HOOK_FILE_IDX_IS_WRONG;
            $this->load( $idx );
            //$this->debug_log();
            if ( ! $this->user_idx || $this->user_idx == currentUser()->idx || $this->user_idx == anonymousUser()->idx ) {
                //currentUser()->debug_log();

                $up = [ 'finish' => 'Y' ];
                if ( ! $this->model ) $up['model'] = $model;
                if ( ! $this->model_idx ) $up['model_idx'] = $model_idx;
                if ( ! $this->code ) $up['code'] = $code;
                if ( $this->user_idx != currentUser()->idx ) $up['user_idx'] = currentUser()->idx;
                if ( $up ) $this->update( $up );
            }
            else {
                return ERROR_HOOK_NOT_YOUR_FILE;
            }
        }
        return OK;
    }

    public function increaseNoOfDownload() {
        return $this->update( ['no_of_download' => $this->no_of_download + 1] );
    }


    /**
     * Returns an array of the file records based on the params
     *
     * The index of 'model,model_idx,code' is not unique. So it always returns an array.
     * @param $model
     * @param $model_idx
     * @param $code
     * @return array|int
     */
    public function get( $model, $model_idx, $code=null ) {

        if ( $code ) $and_code = " AND code='$code' ";
        else $and_code = null;
        return $this->getRecords( "model='$model' AND model_idx=$model_idx $and_code" );
    }


    /**
     * @param $model
     * @param $model_idx
     * @param null $code
     * @return array|int
     */
    public function getFirstImage( $model, $model_idx, $code=null ) {
        if ( $code ) $and_code = " AND code='$code' ";
        else $and_code = null;
        $rows = $this->getRecords( "model='$model' AND model_idx=$model_idx $and_code AND type LIKE 'image/%' LIMIT 1" );
        if ( $rows ) return $rows[0];
        else return [];
    }

    public function loadFirstImage( $model, $model_idx, $code=null ) {
        $file = self::getFirstImage( $model, $model_idx, $code );
        return (new File())->reset($file);
    }


    /**
     * Returns url of uploaded photo.
     *
     * @return string
     *
     * @code
     *          $image = $this->post_data->file()->loadFirstImage()->url();
     * @endcode
     *
     * @change it does not return file name.
     * @change it simply return with idx.
     *
     */
    public function url() {
        if ( $this->exist() ) return get_index_php_url() .  '?route=download&idx=' . $this->idx . '&created=' . $this->created;// . '&name=/' . $this->name;
        else return null;
    }


    public function pres($files, $option=[])
    {
        $ret = [];
        if ( empty( $files ) ) return $ret;
        foreach( $files as $file ) {
            $ret[] = $this->pre( $file, $option );
        }
        return $ret;
    }

    /**
     * Returns ready-to-send-to-browser file (record) data
     *
     * @param $file - file record.
     * @param array $option
     * @return array
     */
    public function pre( $file=null, $option=[] ) {
        if ( $file === null ) $file = $this->getRecord();
        if ( empty($file) ) return [];
        $obj = (new File())->load( $file['idx'] );
        if ( ! $obj->exist() ) return [];
        return [
            'idx' => $file['idx'],
            'model' => $file['model'],
            'model_idx' => $file['model_idx'],
            'code' => $file['code'],
            'name' => $file['name'],
            'type' => $file['type'],
            'size' => $file['size'],
            'no_of_download' => $file['no_of_download'],
            'url' => $obj->url()
        ];
    }


}
