<?php
namespace model\taxonomy;
class Taxonomy extends \model\base\Base  {

    public function __construct()
    {
        parent::__construct();

    }





    /**
     *
     *
     * Return number of rows in the table.
     *
     *
     *
     *
     * @param $cond
     * @return int|null
     */
    public function count( $cond ) {
        if ( empty($cond) ) return ERROR_EMPTY_SQL_CONDITION;
        if ( ! db()->secure_cond( $cond ) ) return ERROR_INSCURE_SQL_CONDITION;
        return db()->result("SELECT count(*) FROM {$this->getTable()} WHERE $cond" );
    }




    /**
     * Return total number of records in the table.
     * @return null
     */
    public function countAll() {
        return $this->count( 1 );
    }




    /**
     *
     *
     *
     * @warning No result return But number.
     *
     * @attention But if there is any error on database query, JSON error message will be saved and displayed to client.
     * @param $cond
     *
     *
     *
     * @return int
     *      0, on success.
     *      integer less than 0 will be return on error.
     *
     *
     */
    public function delete( $cond ) {
        if ( empty($cond) ) return ERROR_EMPTY_SQL_CONDITION;
        if ( ! db()->secure_cond( $cond ) ) return ERROR_INSCURE_SQL_CONDITION;
        db()->query(" DELETE FROM {$this->getTable()} WHERE $cond ");
        return OK;
    }





}