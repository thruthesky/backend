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
    /*
    public function delete( $cond ) {
        if ( empty($cond) ) return ERROR_EMPTY_SQL_CONDITION;
        if ( ! db()->secure_cond( $cond ) ) return ERROR_INSCURE_SQL_CONDITION;
        db()->query(" DELETE FROM {$this->getTable()} WHERE $cond ");
        return OK;
    }

    */



    /**
     * Returns rows of entity table based on the $cond.
     *
     * @WARNING @ATTENTION - This method must NOT be call with unfiltered user input. Meaning the param $cond must NOT take user's input without security filtering.
     *
     * @warning Use this method with caution.
     *
     * @attention All request to get rows from database MUST use this method IF it is only simple 'SELECT', NOT JOIN-SELECT-QUERY.
     *
     * @note it does WEEK 'SQL INJECTION' security check.
     *
     * @param $cond
     * @return array|int
     *
     *          - Array of records will be return on success.
     *          - ERROR_CODE will be returned on error.
     *
     *
     * @note $cond can be
     *
     *      - " id LIKE 'abc%' AND ( email LIKE 'abc%' OR nickname == 'def' ) AND age=44 "
     *      - "1"
     *
     */
    public function loads( $cond )
    {
        if ( empty($cond) ) return ERROR_EMPTY_SQL_CONDITION;
        if ( ! db()->secure_cond( $cond ) ) return ERROR_INSCURE_SQL_CONDITION;
        return db()->rows("SELECT * FROM {$this->getTable()} WHERE $cond");
    }






}