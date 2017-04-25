<?php
/**
 *
 *
 *
 * @file base.php
 *
 * @desc base code for the system.
 *
 *
 *
 *
 *
 *
 *
 */
namespace model\base;
class Base {


    private $table = null;

    public function __construct()
    {

    }



    public function setTable( $table ) {
        $this->table = DATABASE_PREFIX . $table;
        return $this;
    }

    public function getTable() {
        return $this->table;
    }

    public function getModel() {
        return substr( $this->getTable(), strlen( DATABASE_PREFIX ) );
    }


    public function encryptPassword( $str ) {
        return password_hash( $str, PASSWORD_DEFAULT );
    }

    /**
     * Returns true if password matches.
     *
     * @param $plain_text_password
     * @param $encrypted_password
     * @return bool
     *
     * @code
     *      if ( ! $user->checkPassword( in('old_password'), $user->password ) ) return ERROR_WRONG_PASSWORD;
     * @endcode
     */
    public function checkPassword( $plain_text_password, $encrypted_password ) {
        return password_verify( $plain_text_password, $encrypted_password );
    }


    /**
     * Gets and Pre-process the search conditoin based on input parameter or http variables.
     *
     * @param array $option
     * @return array|int
     *
     *
     *      - $option['org_limit']      will have a number of 'no_of_items_in_one_page'
     *      - $option['limit']          will have a SQL limit clause like "LIMIT 30,10"
     *
     */
    public function processSearchVariables( $option = [] ) {

        if ( isset( $option['done'] ) ) return $option;     // once process, it does not process again.

        if ( empty($option) ) {
            $option = [
                'select' => in('select'),
                'from' => in('from'),
                'limit' => in('limit'),
                'where' => in('where'),
                'bind' => in('bind'),
                'order' => in('order'),
                'page' => in('page')
            ];
        }



        /**
         * limit
         */
        if ( isset($option['limit']) && $option['limit'] ) {
            if ( ! is_numeric( $option['limit'] ) ) return ERROR_LIMIT_IS_NOT_NUMERIC;
            if ( $option['limit'] < 0 ) $option['limit'] = DEFAULT_NO_OF_PAGE_ITEMS;
        }
        else $option['limit'] = DEFAULT_NO_OF_PAGE_ITEMS;

        if ( $option['limit'] > MAX_NO_OF_ITEMS ) return ERROR_MAX_NO_OF_ITEMS;




        /**
         * from
         *
         */
        // if ( ! is_numeric( $option['page'] ) ) return ERROR_FROM_IS_NOT_NUMERIC;

        if ( isset($option['page']) && $option['page'] ) {
            // if ( req['page'] ) {
            //     let page = req['page'] > 0 ? req['page'] : 1;
            //     let limit = req.limit;
            //     req.from =  ( page - 1 ) * limit;
            //     delete( req.page );
            // }
            if ( is_numeric( $option['page'] ) && $option['page'] > 0 ) $page = $option['page'];
            else $page = 1;
            $option['from'] = ( $page - 1 ) * $option['limit'];
        }
        else if ( ! isset($option['page']) || ! is_numeric($option['from'] ) || $option['from'] < 0 ) {
            $option['from'] = 0;
        }


        $option['org_limit'] = $option['limit'];


        //
        $option['limit'] = "LIMIT $option[from], $option[limit]";

        //


        if ( ! isset($option['select']) || empty( $option['select']) ) $option['select'] = '*';

        //
        if ( isset($option['order']) && $option['order'] ) {
            if ( ! db()->secure_cond( in('order') ) ) return ERROR_INSCURE_SQL_CONDITION;
            $option['order'] = 'ORDER BY ' . in('order');
        }
        else $option['order'] = null;


        $option['done'] = TRUE;         /// Mark as

        return $option;
    }

}
