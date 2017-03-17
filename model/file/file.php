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
     *
     * @param $fileinfo - holds all information about saving files like model, model_idx, code, etc.
     * @param $userfile
     * @return array|int|number
     */
    public function save( $fileinfo, $userfile )
    {
        if ( isset($fileinfo['model']) ) $model = $fileinfo['model'];
        else return ERROR_MODEL_IS_EMPTY;
        if ( isset($fileinfo['model_idx']) ) $model_idx = $fileinfo['model_idx'];
        else return ERROR_MODEL_IDX_IS_EMPTY;

        $code = isset($fileinfo['code']) ? $fileinfo['code'] : null;

        if ( ! isset($userfile['error'])  ) return ERROR_UPLOAD_ERROR_NOT_SET;
        debug_log($userfile);
        if ( $userfile['error'] === UPLOAD_ERR_OK && $userfile['size'] && $userfile['tmp_name'] ) {
            // uploading from web-browser to web-server was successfully done.
            // continue your work.
        }
        else {
            return $this->errorResponse( $userfile['error'] );
        }


        // delete old files that are not hooked.
        $this->deleteUnhooked();


        $src = $userfile['tmp_name'];
        if ( ! file_exists( $src ) ) return ERROR_UPLOAD_FILE_NOT_EXIST;


        $idx = $this->set('name',$userfile['name'])
            ->set('size', $userfile['size'])
            ->set('type',$userfile['type'])
            ->set('model', $model )
            ->set('model_idx', $model_idx )
            ->set('code', $code )
            ->set('user_idx', currentUser()->idx )
            ->create();

        if ( is_error($idx) ) return ERROR_FILE_UPLOAD_CREATE_IDX_FAILED;



        $dst = DIR_UPLOAD . "/$idx";
        debug_log("move_uploaded_file($src, $dst)");

        if ( file_exists( $dst ) ) return ERROR_UPLOAD_FILE_EXIST;
        if ( is_test() ) $re = @copy( $src, $dst );
        else $re = @move_uploaded_file( $src, $dst );

        if( ! $re ) {
            $error = error_get_last();
            $this->load($idx)->delete();
            return [ 'code' => ERROR_MOVE_UPLOADED_FILE, 'message' => $error['message'] ];
        }

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
            foreach ( $files as $file ) {
                $this->delete( $file['idx'] );
            }
        }

    }

    /**
     * @note if parameter idx is empty then it will delete the file of the object
     *
     * @attention it overrides entity()->delete().
     * @param null $idx - file.idx to delete
     * @return number|void 
     * @attentions it doesn't return anything so you will not know if the deletion is success or not
     */
    public function delete( $idx = null ) {

        if( $idx ) $this->reset($idx);
                // entity()->load($idx)->delete();
        $file_path = $this->path( $this->idx );
        if(!file_exists($file_path)) return ERROR_UPLOAD_FILE_EXIST;
        @unlink( $file_path );
        parent::delete();
        debug_log(">>> $file_path deleted");
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
     */
    public function deleteBy( $model, $model_idx, $code=null ) {
        if ( $code ) $and_code = "AND code='$code'";
        else $and_code = null;
        $files = db()->rows("SELECT idx FROM {$this->getTable()} WHERE model='$model' AND model_idx=$model_idx $and_code");
        if ( $files ) {
            foreach ( $files as $file ) {
                $this->delete( $file['idx'] );
            }
        }
    }

    public function path( $idx ) {

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
}