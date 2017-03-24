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
     * @note if you need to get total number of record, use countSearch()
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
     * @code        simplest example
     *
                    $re = taxonomy()->setTable('meta')->search( [] );
     *
     * @endcode
     */
    public function search( $option ) {

        if ( empty($this->getTable()) ) return ERROR_TABLE_NOT_SET;


//        debug_log($option);


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


        if ( ! isset( $option['select'] ) ) $option['select'] = '*';

        //
        if ( isset( $option['order'] ) ) {
            if ( ! db()->secure_cond( in('order') ) ) return ERROR_INSCURE_SQL_CONDITION;
            $order = 'ORDER BY ' . in('order');
        }
        else $order = null;



        //
        $where = $this->getSearchCondition( $option );
        if ( is_error( $where ) ) return $where;

        $q = "SELECT $option[select] FROM {$this->getTable()} $where $order $limit";

        debug_log("taxonomy_query:");

        return db()->rows( $q );

    }

    public function countSearch( $option ) {
        $where = $this->getSearchCondition( $option );
        if ( is_error( $where ) ) return $where;
        $row = db()->row("SELECT COUNT(*) as cnt FROM {$this->getTable()} $where");
        return $row['cnt'];
    }


    private function getSearchCondition( $option ) {

        $where = null;
        if ( isset($option['where']) && ! empty($option['where']) ) {

            if ( ! db()->secure_bind_statement($option['where']) ) return ERROR_UNSECURE_STATEMENT_CONDITION;
            if ( strpos($option['where'], '?') === false ) return ERROR_MISSING_BINDING_MARK;
            if ( ! isset( $option['bind'] ) || empty( $option['bind'] ) ) return ERROR_SQL_BIND_NOT_SET;

            $count_marks = substr_count($option['where'], '?');
            $count_binds = count( explode(',', $option['bind']) );


            if ( $count_marks > $count_binds ) return ERROR_SEARCH_BIND_LACK;
            if ( $count_marks < $count_binds ) return ERROR_SEARCH_MARK_LACK;
            // if ( $count_marks != $count_binds ) return ERROR_SQL_WHERE_BIND_MISMATCH;
            $where = "WHERE $option[where]";

            //
            $bind = $option['bind'];
            $binds = explode(',', $bind);
            foreach( $binds as $value ) {
                $quoted = db()->quote( $value );
                $pos = strpos( $where, '?' );
                $where = substr_replace( $where, $quoted, $pos, 1 );
            }
        }
        return $where;
    }


    /**
     *
     * Retuerns an array of idx based on the cond.
     * @param $cond
     *
     * @warning no table set test. If table is not set, then it would result in unexptected query error.
     * @return array
     */
    public function idxes( $cond ) {
        $idxes = [];
        $table = $this->getTable();
        $rows = db()->rows("SELECT idx FROM $table WHERE $cond");
        foreach( $rows as $row ) {
            $idxes[] = $row['idx'];
        }
        return $idxes;
    }

    /**
     * Returns the records based on the condition
     * @param $cond
     * @param string $select
     * @return array|int
     *
     * @code
     *        $record['files'] = (new File())->getRecords( " model='post' AND model_idx=$record[idx] ", 'idx, type, name');
     * @endcode
     *
     *
     */
    public function getRecords( $cond, $select="*") {
        $table = $this->getTable();
        return db()->rows("SELECT $select FROM $table WHERE $cond ");
    }

}