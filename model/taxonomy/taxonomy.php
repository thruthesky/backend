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
        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;
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
    /*
    public function loads( $cond )
    {
        if ( empty($cond) ) return ERROR_EMPTY_SQL_CONDITION;
        if ( ! db()->secure_cond( $cond ) ) return ERROR_INSCURE_SQL_CONDITION;
        return db()->rows("SELECT * FROM {$this->getTable()} WHERE $cond");
    }


    */


    /**
     *
     * @param $option
     *
     * @warning $option['where'] is user-made query.
     *      And this method does the same way of how PDO::bindParam() does for the safe Query.
     *      User-made conditions are all escaped by PDO::quote()
     *
     *
     * @return mixed
     *
     *      - use is_error() to check if it was successful.
     *      - ERROR CODE ( number less than 0 ) will be returned on error.
     *      - array ( including empty array ) will be return on success.
     *
     *
     *
     * @code
                    $re = $this->route( 'user.list', [
                        'from' => 2,
                        'limit' => 3,
                        'where' => "name LIKE ? AND gender=?",
                        'bind' => "%name%,M",
                        'order' => 'idx ASC, name DESC'
                    ]);
     * @endcode
     *
     */
    public function search( $option ) {

        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;

        /**
         *
         */
        if ( isset( $option['from'] ) ) {
            if ( is_numeric( $option['from'] ) ) $from = $option['from'];
            else return ERROR_FROM_IS_NOT_NUMERIC;
        }
        else $from = 0;

        if ( isset( $option['limit'] ) ) {
            if ( is_numeric( $option['limit'] ) ) $limit = $option['limit'];
            else return ERROR_LIMIT_IS_NOT_NUMERIC;
        }
        else $limit = DEFAULT_NO_OF_PAGE_ITEMS;

        $limit = "LIMIT $from, $limit";

        //
        if ( isset( $option['where'] ) ) {
            if ( ! db()->secure_bind_statement($option['where']) ) return ERROR_UNSECURE_STATEMENT_CONDITION;
            $where = "WHERE $option[where]";
        }
        else $where = null;

        if ( ! isset( $option['select'] ) ) $option['select'] = '*';

        //
        if ( isset( $option['order'] ) ) {
            if ( ! db()->secure_cond( in('order') ) ) return ERROR_INSCURE_SQL_CONDITION;
            $order = 'ORDER BY ' . in('order');
        }
        else $order = null;



        //
        if ( isset( $option['bind'] ) ) {
            $bind = $option['bind'];
            $binds = explode(',', $bind);
            foreach( $binds as $value ) {
                $quoted = db()->quote( $value );
                $pos = strpos( $where, '?' );
                if ( $pos === false ) return ERROR_SQL_WHERE_BIND_MISMATCH;
                $where = substr_replace( $where, $quoted, $pos, 1 );
            }
        }

        //
        if ( strpos($where, '?') ) return ERROR_SQL_WHERE_BIND_MISMATCH;

        $q = "SELECT $option[select] FROM {$this->getTable()} $where $order $limit";

        return db()->rows( $q );

    }




}