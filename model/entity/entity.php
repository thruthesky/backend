<?php
/**
 *
 * @attention Database Query must be inside this class only.
 *
 *
 *
 */
namespace model\entity;
$_cache_entity_record = [];
$_cache_entity_record_count = [];
class Entity extends \model\taxonomy\Taxonomy  {

    private $record = [];



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
     * @return mixed
     *
     *      - if error, error code will be return.
     *          -- if there is no data, that is not error. that's just a success with no data.
     *      - if success, array will be returned.
     *
     *      - if there is no data, empty array will be returned.
     *
     *
     *
     */
    public function load( $what ) {


        if ( empty($what) ) return ERROR_EMPTY_SQL_CONDITION;

        $table = $this->getTable();


//        di($what);

        if ( $cache = $this->getCacheEntity( $what ) ) {      /// Check if cache exists.

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
     * @param $data
     * @return array|mixed
     *      - ERROR CODE ( < 0 ) will be return on error.
     *      - Array will be return on success.
    - Success return format
    Array
    (
    [session_id] => 943-fccb4a3fbfd77f7606289c6437400be8
    )
     *
     * @see readme for detail.
     */
    public function create( $data ) {



        $record['created'] = time();
        return db()->insert( $this->getTable(), $data );


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
     * @return \model\entity\Entity
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
     * @endcode
     *
     *
     */
    public function reset( $idx ) {
        $this->record = [];
        if ( is_numeric($idx) ) $this->load( $idx );
        else if ( is_array( $idx ) ) $this->record = $idx;

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
    public function increaseResetCount( $what ) {
        global $_cache_entity_record_count;
        $table = $this->getTable();
        if ( isset($_cache_entity_record_count[ $table ]) && isset($_cache_entity_record_count[ $table ][ $what ]) ) {
            $_cache_entity_record_count[ $table ][ $what ]++;
        }
        else $_cache_entity_record_count[ $table ][ $what ] = 1;

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
     * @warning it returns a value now.
     *
     * @return bool|\PDOStatement - same as PDO::query. If there is error, FALSE will be return.
     *
     */
    public function update( $record ) {
        $record['updated'] = time();
        if ( $this->idx ) {
            return db()->update( $this->getTable(), $record, "idx={$this->idx}");
        }
        return FALSE;
    }






}
