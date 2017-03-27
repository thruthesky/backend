<?php
/**
 *
 * @attention Database Query must be inside this class only.
 *
 *
 *
 */
namespace model\entity;

/**
 *
 * Entity caches in memory for what it hah loaded. Meaning, once you load an entity it is cached in the memory variable.
 *
 * $_cache_entity_record contains the record of the entity.
 *
 * When you try to load an entity that has already loaded, it checks if the entity exists on the cache and if so, it does not
 *
 * load it from database, but just return an Entity object with the cached record.
 *
 * $_cache_entity_record_count is the hit count of how many times the cached entity had been used.
 *
 *
 */
use model\user\User;

$_cache_entity_record = [];
$_cache_entity_record_count = [];

/**
 * Class Entity
 * @package model\entity
 */
class Entity extends \model\taxonomy\Taxonomy  {

    private $record = [];


    public function __construct()
    {
        parent::__construct();

    }



    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->record[$name] = $value;
    }

    public function __get($name)
    {
        if ( ! is_array( $this->record ) ) return null;
        if (array_key_exists($name, $this->record)) {
            return $this->record[$name];
        }
        else return null;
    }



    public function meta() {
        return meta_proxy( $this->getTable(), $this->idx );
    }
    public function file() {
        return file_proxy( $this->getModel(), $this->idx );
    }


    public function getRecord() {
        return $this->record;
    }
    /**
     *
     * @note the difference between setter and set() is that set() returns $this.
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value) {
        $this->record[$name] = $value;
        return $this;
    }



    public function debug_log() {
        debug_log( $this->record );
        return $this;
    }


    /**
     *
     *
     * To check if the record is set or not set.
     *
     * @warning this return FALSE if the user didn't logged in. or if the user is anonymous.
     * @warning
     *
     *      You cannot do something like below to check entity exist.
     *
     *
     *      if ( ! $this->load( $id ) ) return ERROR_USER_NOT_EXIST;
     *
     *      You must do
     *
     *      if ( ! $this->load( $id )->exist() ) return ERROR_USER_NOT_EXIST;
     *
     *
     * @return number|boolean
     *      - FALSE on error.
     *      - number of record idx
     */
    public function exist()
    {
        if ( empty( $this->record ) ) return FALSE;
        if ( ! isset( $this->record['idx'] ) ) return FALSE;
        return $this->idx;
    }


    /**
     * Returns a record.
     *
     * @attention @important load() resets the $record.
     * @attention Once the $record has loaded and it has same id or idx of '$what', then it just returns $this object without reloading.
     *
     * @param $what
     *              - If it is numeric, then it is idx. so, this method will get the record on the idx.
     *              - If it is one word string, then it is an 'ID'
     *              - If it is a string with ' ', =, <, >, then it assumes that is is a WHERE SQL clause.
     * @param bool $reload - If true, it get all the data from database NOT from 'memory cache'.
     * @return $this - if error, error code will be return.
     *
     * - if error, error code will be return.
     * -- if there is no data, that is not error. that's just a success with no data.
     * - if success, array will be returned.
     *
     * - if there is no data, empty array will be returned.
     */
    public function load( $what, $reload=false ) {


        if ( empty($what) ) return ERROR_EMPTY_SQL_CONDITION;

        $table = $this->getTable();
        if ( empty( $table ) ) return ERROR_TABLE_NOT_SET;



//        di($what);

        if ( $reload == false && $cache = $this->getCacheEntity( $what ) ) {      /// Check if cache exists.

            $this->reset( $cache );   /// Reset the cache.
            $this->increaseResetCount( $what );

            return $this;
        }
        if ( is_numeric($what) ) $cond = "idx=$what";
        else $cond = "id = '$what'";

        $this->record = db()->row("SELECT * FROM $table WHERE $cond");

        $this->setCacheEntity( $what, $this->record );
        return $this;
    }


    /**
     *
     * Loads multiple entities.
     *
     * @param $idxes
     *
     * @see Entity_Test::multi_load()
     *
     *
     * @return mixed
     *      if there is error, error code will be returned.
     */
    public function loads( $idxes, $reload=false ) {
        $rets = [];
        if ( empty( $idxes ) ) return $rets;
        foreach ( $idxes as $idx ) {
            $this_entity = $this->load( $idx, $reload );
            if ( is_error( $this_entity ) ) return $this_entity; // error
            $rets[] = clone( $this_entity );
        }
        return $rets;
    }

    /**
     *
     * Loads entity data into $this->record.
     *
     * @param $cond - SQL Condition
     * @warning this method must be called only internally for security reason. This should not accept user made query condition.
     *
     * @return $this
     *
     */
    public function loadQuery( $cond ) {
        $table = $this->getTable();
        if ( empty( $table) ) return ERROR_TABLE_NOT_SET;
        $this->record = db()->row("SELECT * FROM $table WHERE $cond");
        return $this;
    }


    /**
     *
     * @see Entity_Test::multi_load()
     *
     * @param $cond
     * @return mixed
     */
    public function loadsQuery( $cond ) {
        return $this->loads( $this->idxes( $cond ) );
    }






    private function getCacheEntity( $what ) {
        global $_cache_entity_record;
        $table = $this->getTable();
        if ( isset( $_cache_entity_record[ $table ][ $what ] ) ) return $_cache_entity_record[ $table ][ $what ];
        return null;
    }

    private function setCacheEntity( $what, $record ) {

        global $_cache_entity_record;
        $table = $this->getTable();
        /// Save in memory cache
        if ( $this->record ) $_cache_entity_record[$table][ $what ] = $this->record;
    }

    /**
     *
     * Delete cache
     *
     * @param $what
     */
    private function deleteCacheEntity( $what ) {
        global $_cache_entity_record;
        $table = $this->getTable();
        // di( "_cache_entity_record[$table][ $what ]" );
        unset( $_cache_entity_record[$table][ $what ]);
    }


    /**
     *
     * Returns $this->idx or $idx->id or OK ( in this order ) after clearing the cache.
     *
     *
     * @return mixed
     *
     */
    public function deleteCache() {

        $idx_backup = $this->idx;
        $id_backup = $this->id;

        if ( $this->idx ) $this->deleteCacheEntity( $this->idx );
        if ( $this->id ) $this->deleteCacheEntity( $this->id );
        $this->reset( [] );

        if ( $idx_backup ) return $idx_backup;
        else if ( $id_backup ) return $id_backup;
        else return OK;
    }


    /**
     *
     * @param null $record
     *
     *
     *
     * @return number
     *      - number of ERROR CODE ( < 0 ) will be return on error.
     *      - number of entity idx ( > 0 ) on success.
     *      - ERROR if somehow failed on Insert.
     *
     * @note user is_success() to check if it was success()
     *
     * @see readme for detail.
     *
     */
    public function create( $record = null ) {
        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;
        if ( is_array($record) ) $this->record = $record;
        if ( empty( $this->record ) ) return ERROR_RECORD_NOT_SET;
        $this->record['created'] = time();
        return db()->insert( $this->getTable(), $this->record );
    }




    /**
     *
     *
     * Sets the record to operate with.
     *
     * @note Once you set the data, it will return the Entity object.
     *
     * @usage Use this method when you have 'idx' fields of an entity or a record of an entity.
     *
     * @param $idx number|array
     *
     *  if it is a numeric, it assumes as 'idx'. it gets the record of 'idx' on the table and saves the records into $record.
     *  if it is an array, it assumes it is already the record, so it just sets to $record.
     *
     * @return $this
     *
     *  it returns $this->record, meaning,
     *      - if there is no record by 'idx' and null|empty will be return.
     *      - if the $idx is not an array but empty, then it will return empty.
     *
     * @code
    $user_idx = $this->create( $data );
    $this->reset( $user_idx );
     * @endcode
     *
     * @code
     *      $this->reset( [ 'a'=>'b' ] );
     *      $this->reset( user( 123 ) );
     *      this->reset( user( $session_id ) );

     * @endcode
     *
     *
     */
    public function reset( $idx ) {
        $this->record = [];
        if ( is_numeric($idx) ) $this->load( $idx );
        else if ( is_array( $idx ) ) $this->record = $idx;
        else if ( $idx instanceof User ) $this->record = $idx->getRecord();

        return $this;
    }

    /**
     * Returns reset count of a record.
     *
     * @note it tracks memory reset cache count.
     *
     *
     * @param $what
     */
    private function increaseResetCount( $what ) {
        global $_cache_entity_record_count;
        $table = $this->getTable();
        if ( isset($_cache_entity_record_count[ $table ]) && isset($_cache_entity_record_count[ $table ][ $what ]) ) {
            $_cache_entity_record_count[ $table ][ $what ]++;
        }
        else $_cache_entity_record_count[ $table ][ $what ] = 1;

        // di($_cache_entity_record_count);
    }


    public function getResetCount( $what ) {
        global $_cache_entity_record_count;
        $table = $this->getTable();
        if ( isset($_cache_entity_record_count[ $table ]) && isset($_cache_entity_record_count[ $table ][ $what ]) ) {
            return $_cache_entity_record_count[ $table ][ $what ];
        }
        return 0;
    }


    /**
     * @param $record
     *
     * @param bool $reload
     *
     *      - If it is true, then it deletes $this->record and reload the data.
     *      - If it is false, then it does NOT delete $this->record and does NOT reload the data.
     *
     * @warning If $reload is not true, then the Entity object still have the data before it updates
     *          And you are working with it.
     *          Be sure you are handling Entity data before update.
     *
     * @note You can pass $reload = false, when you do some heavy update of multiple record and you do not need the updated data.
     *
     * @return bool|number - FALSE on database error.
     *
     * - FALSE on database error.
     * - FALSE on logical error. If entity idx does not exist.
     * - TRUE if there is no modified/deleted row.
     * - TRUE of rows that are modified/deleted.
     * @attention it does not support chaining like below
     *
     *      user()->load(...)->set(...)->update()
     *
     *      because when you do this, you will update more data even that are not needed.
     *
     *      So,
     *
     *      user()->load()->update( [ ... ] )
     *
     *      is the only way to minimize the upload data.
     *
     * @warning it returns a value now.
     *
     * @note User === to check if it is error.
     *
     *
     * @code
     *
     *                  $this->update( $info, false );

     * @endcode
     *
     *
     */
    public function update( $record, $reload = true ) {
        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;
        $record['updated'] = time();
        if ( $this->idx ) {
            $re = db()->update( $this->getTable(), $record, "idx={$this->idx}");
            if ( $re === FALSE ) return FALSE;
            else {
                if ( $reload ) $this->load( $this->deleteCache() ); /// @attention it reloads the record if $reload is set.
                return TRUE;
            }
        }
        else return FALSE;
    }






    /**
     *
     * Deletes the record.
     *
     * @attention all the entity delete is handled by this method.
     *
     *
     * @attention if there is any error on database query, error code will be return.
     *
     * @warning after deleting an entity using this method all the information will be invalid. you cannot use $this->$idx after this method
     *
     * @warning when you do something like below
     *
     *          $this->load( ... )->delete()
     *
     *      The '$this->record' becomes empty. so, you should do something like below.
     *
     *          $this->set('abc', 'def');
     *          $this->delete();            //      => This empty $record. So, nothing to create.
     *          $this->create();
     *
     * @return number
     *
     *      - Use is_success() or is_error() to check the result.
     *
     *      - number of error code on error
     *      - 0 (OK) on success.
     *
     * @code
     *
            user()->loadBySessionId( $session_id )->delete();
     * @endcode
     *
     * @code Example of entity->delete();
     *
            $user->delete();
            $resigned = user()->load( $session_id  );
            if ( $resigned->exist() ) { ... Error on delete ... }
            else { ... Success on delete ... }
     *
     * @endcode
     *
     *
     * @code Example of entity()->delete()
            return entity()
                ->setTable( $this->getTable() )
                ->loadQuery("model = '$model' AND model_idx = $model_idx $and_code")
                ->delete();
     * @endcode
     *
     */
    public function delete() {
        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;
        if ( ! $this->exist() ) return ERROR_USER_NOT_SET;
        $idx = $this->idx;
        db()->query(" DELETE FROM {$this->getTable()} WHERE idx=$idx ");
        /// Reset ( delete ) all the caches.
        $this->deleteCache();
        return OK;
    }


    /**
     * @param $entity - this is the entity to hook the (uploaded) files with. it can be user, post, etc.
     * @param null $code - optional, code.
     *
     * @attention - a file can be hooked(attached) to an entiy IF the file is belongs to the entity or IF the owner of the file is anonymous.
     * @return int
     */
    public function hookUpload( Entity $entity, $code = null ) {
        return ( new \model\file\File() )->hook( $entity->getModel(), $entity->idx, $code, in('file_hooks') );

    }



}
