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
     *
     *
     * @return $this - if error, error code will be return.
     *
     * - if error, error code will be return.
     * -- if there is no data, that is not error. that's just a success with no data.
     * - if success, array will be returned.
     *
     *
     */
    public function load( $what ) {


        if ( empty($what) ) return ERROR_EMPTY_SQL_CONDITION;

        $table = $this->getTable();
        if ( empty( $table ) ) return ERROR_TABLE_NOT_SET;

        if ( is_numeric($what) ) $cond = "idx=$what";
        else $cond = "id = '$what'";

        $this->record = db()->row("SELECT * FROM $table WHERE $cond");


        return $this;
    }


    /**
     *
     * Loads multiple entities.
     *
     * @note This must be here( instead of taxonomy ) to support entity.
     *
     * @param $idxes
     *
     * @see Entity_Test::multi_load()
     *
     *
     * @return mixed
     *      if there is error, error code will be returned.
     */
    public function loads( $idxes ) {
        $rets = [];
        if ( empty( $idxes ) ) return $rets;
        foreach ( $idxes as $idx ) {
            $this_entity = $this->load( $idx );
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
     *
     * @note This must be here( instead of taxonomy ) to support entity.
     * @see Entity_Test::multi_load()
     *
     * @param $cond
     * @return mixed
     *
     * @code            To load all entities of the taxonomy.
     *
                        $forums = config()->loadsQuery( true );
     *
     * @endcode
     */
    public function loadsQuery( $cond ) {
        return $this->loads( $this->idxes( $cond ) );
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
     * - ERROR CODE Numbers less than 0 for error.
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
    public function update( $record, $reload=true ) {
        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;
        $record['updated'] = time();
        if ( $this->idx ) {
            $re = db()->update( $this->getTable(), $record, "idx={$this->idx}");
            if ( $re === FALSE ) return FALSE;
            else {
                if ( $reload ) {
                    $this->reset( $this->idx );
                }
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


    /**
     *
     * Returns an array of category objects of children.
     *
     *
     *
     * @note This must be here( instead of taxonomy ) to support entity.
     *
     * @note $reload is set 'false' by default. If you need to memory cache, you need to set it true.
     *
     * @param $parent_idx
     * @param bool $reload
     * @return array - Array of Category object.
     */
    public function loadChildren( $parent_idx=null, $reload=true ) {
        if ( $parent_idx === null ) $parent_idx = $this->idx;
        $idxes = $this->getChildren( $parent_idx );
        $ret = [];
        foreach ( $idxes as $idx ) {
            $ret[] = clone $this->load( $idx, $reload );
        }
        return $ret;
    }


    /**
     * Returns an array of category objects of the brothers of the parent.
     *
     *
     * @note This must be here( instead of taxonomy ) to support entity.
     *
     * @note $reload is set 'false' by default. If you need to memory cache, you need to set it true.
     *
     *
     * @param $parent_idx
     * @param bool $reload
     * @return array
     */
    public function loadBrothers( $parent_idx, $reload = true ) {
        if ( $parent_idx === null ) $parent_idx = $this->idx;
        $idxes = $this->getBrothers( $parent_idx );
        $ret = [];
        foreach ( $idxes as $idx ) {
            $ret[] = clone $this->load( $idx, $reload );
        }
        return $ret;
    }


    /**
     *
     *
     * @note This must be here( instead of taxonomy ) to support entity.
     *
     * @note $reload is set 'false' by default. If you need to memory cache, you need to set it true.
     *
     * @param null $self_idx
     * @param bool $self_include
     * @param bool $reload
     * @return array
     */
    public function loadParents( $self_idx=null, $self_include = false, $reload = true ) {
        if ( $self_idx === null ) $self_idx = $this->idx;
        $idxes = $this->getParents( $self_idx, $self_include );
        $ret = [];
        foreach ( $idxes as $idx ) {
            $ret[] = clone $this->load( $idx, $reload );
        }
        return $ret;
    }


}
