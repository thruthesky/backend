<?php
/**
 *
 */

namespace model\user;
class User extends \model\entity\Entity {

    const PUBLIC_FIELDS = [ 'id', 'name', 'middle_name', 'last_name', 'nickname', 'gender', 'birth_year', 'birth_month', 'birth_day', 'country', 'province', 'city' ];

    public function __construct()
    {
        parent::__construct();
        $this->setTable('user');
    }


    /**
     *
     * @Warnung Anonymous user is not considered as logged in.
     *
     * @return bool|number
     */
    public function logged() {
        if ( ! $this->exist() ) return FALSE;
        if ( $this->id == ANONYMOUS_ID ) return FALSE;
        return TRUE;
    }

    /**
     *
     * Return unique session id for each user after reset it.
     *
     * @attention it does not check if the user has authenticated or not.
     *
     * @work
     *          1. It just generate a new session id based on $this->record
     *          2. save the session id into database table record.
     *          3. return it.
     *
     * @return string
     *
     * @code
     *      user( $id )->getSessionId();
     * @endcode
     *
     */
    public function getSessionId() {

        if ( $this->idx ) {
            $n = $this->idx;
            $i = $this->id;
            $p = $this->password;
            $r = $this->created;
            $t = md5(uniqid(rand(), true));
            $session_id = $n . '-' . md5( "$i,$r,$t,$n,$p" );
            $this->update( ['session_id' => $session_id] );
            return $session_id;
        }
        else {
            return ERROR_USER_NOT_SET;
        }

    }

    /**
     * Return TRUE $what has session id format.
     *
     * @param $what
     *
     * @return bool
     */
    public function isSessionId( $what ) {
        if ( empty($what) ) return FALSE;
        if ( ! strpos( $what, '-' ) ) return FALSE;
        list( $idx, $md5 ) = explode('-', $what);
        if ( ! is_numeric( $idx ) ) return FALSE;
        if ( is_numeric( $md5 ) ) return FALSE;
        if ( strlen($md5) != 32 ) return FALSE;
        return TRUE;
    }


    /**
     *
     * Overrides of entity()->load()
     *
     * @param $what
     *      - if it is session id, it load data based on session id.
     *      - or else, it will do entity->load()
     *
     * @return $this
     *
     * @code example
     *          $user = user()->load( in( 'session_id' ) );
     * @endcode
     *
     */
    public function load( $what, $reload = true ) { // for variable compatibilities with Entity

        if ( $this->isSessionId($what) ) return $this->loadBySessionId( $what );
        else {
            return parent::load( $what, $reload );
        }
    }




    /**
     *
     * loads a user by session id.
     * @warning it does what 'load()' does.
     * @param $session_id
     * @return $this
     * @code
     *         $this->load_by_session_id( in('session_id') );
     * @endcode
     */
    private function loadBySessionId( $session_id ) {
        if ( empty($session_id) ) return ERROR_SESSION_ID_EMPTY;
        $user = $this->loadQuery( "session_id='$session_id'");
        if ( empty($user) ) return ERROR_WRONG_SESSION_ID;
        return $user;
    }





    /**
     *
     * Returns true if the login user is ADMIN.
     *
     * @return bool
     */
    public function isAdmin() {
        return $this->id == ADMIN_ID;
    }
    public function isAnonymous() {
        return $this->id == ANONYMOUS_ID;
    }



    /**
     *
     * This sets currently logged in user of the input '$id'
     *
     * And returns a new Session ID of a user.
     *
     *
     * @use this method when you want to login a user without password.
     *
     * @attention It generates a FAKE session id and save it into user record and returns it.
     * @attention It does not check user's password.
     * @attention previous session-id will be invalid.
     *
     *
     * @changed March 10, 2017. It does not return session_id.
     *
     * @param $id
     * @return int
     *
     *      - int as error code on error
     *      - OK on success.
     *
     *
     * @code
     *
     *      $params['session_id'] = user()->forceLogin('admin');
     *
     * @endcode
     *
     *
     *
     */
    public function forceLogin( $id ) {
        if ( empty($id ) ) return ERROR_USER_ID_EMPTY;
        $user = $this->load( $id );
        if ( ! $user->exist() ) return ERROR_USER_NOT_EXIST;
        setCurrentUser( $user );

        // return $this->getSessionId();

        return OK;
    }



    /**
     *
     *
     * @param $data - user record data to create.
     *
     *      $data['password'] is the password of the user and it must be in plain-text.
     *
     * @return mixed
     *
     *      - ERROR CODE ( < 0 ) will be return on error.
     *      - string of session id will be return on success.
     *
     * @see readme for detail.
     */
    public function create( $data = null ) {

        if ( empty( $data['id'] ) ) return ERROR_USER_ID_EMPTY;
        if ( empty( $data['password'] ) ) return ERROR_PASSWORD_EMPTY;

        $data['password'] = $this->encryptPassword( $data['password'] );


        if ( user( $data['id'] )->exist() ) return ERROR_USER_EXIST;


        $meta = null;
        if ( array_key_exists( 'meta', $data ) ) {

            $meta = $data['meta'];
            unset( $data['meta'] );

        }


        $user_idx = parent::create( $data );

        if ( $user_idx <= 0 ) return $user_idx;

        $this->reset( $user_idx );

        if ( $meta ) {

            meta()->create( $this->getTable(), $user_idx, $meta );

        }

        return $this->getSessionId();

    }


    /**
     * Updates user info.
     * @attention
     *
     *
     * @param $record
     * @param bool $reload
     * @return boolean
     *
     *      - TRUE on success
     *      - FALSE on failure
     *
     *
     */
    public function update( $record, $reload = true ) {

        $meta = null;
        if ( array_key_exists( 'meta', $record ) ) {
            $meta = $record['meta'];
            unset($record['meta']);
        }


        if ( $record ) {                        // If the user has something to update?
            $re = parent::update( $record, $reload );
            if ( empty( $re ) ) return FALSE;   // something happened when updating.
        }

        if ( $meta ) {
            $this->meta()->set( $meta );
        }

        return TRUE;
    }



    /**
     *
     *
     * Updates user login information.
     *
     *  - It leaves login ip/time/count ( how many times the user has logged in )
     */
    public  function  updateLoginInformation() {

        $info = [];
        if ( isset( $_SERVER['REMOTE_ADDR'] ) ) $info['login_ip'] = $_SERVER['REMOTE_ADDR'];
        else $info['login_ip'] = '';

        $info['login_stamp'] = time();

        if ( $this->login_count ) $info['login_count'] = $this->login_count + 1;
        else $info['login_count'] = 1;

        $this->update( $info, false );

    }


    /**
     *
     * Return available user information for the client ( who request the user information )
     *
     * If the requesting user is admin, then he will get the full information.
     * If the requesting user is requesting the user information of his own, then he will get the full record.
     * If NOT, then the user will only get limited information for security and privacy.
     *
     * @param $record
     * @return array
     */
    public function getAvailableData( $record ) {


        if ( currentUser()->isAdmin() ) return $record;
        else if ( $record['idx'] == currentUser()->idx ) return $record;

        $ret = [];
        foreach( self::PUBLIC_FIELDS as $field ) {
            if ( array_key_exists( $field, $record ) ) $ret[ $field ] = $record[ $field ];
        }
        return $ret;
    }

    public function pre() {

        $record = $this->getRecord();
        unset( $record['password'], $record['session_id'] );
        $record['meta'] = meta()->get( $this->getTable(), $record['idx']);

        $record = $this->getAvailableData( $record );

        $record['primary_photo_idx'] = $this->getPrimaryPhotoIdx();

        return $record;
    }

    public function getPrimaryPhotoIdx() {
        //debug_log("user::getPrimaryPhotoIdx()");
        $photo = $this->primaryPhoto();
        //$photo->debug_log();
        if ( $photo->exist() ) return $photo->idx;
        return 0;
    }

    public function primaryPhoto() {
        return ( new \model\file\File() )->loadQuery("user_idx={$this->idx} AND code='". BACKEND_PRIMARY_PHOTO ."'");
    }

    /**
     *
     * Returns an array of users after sanitizing.
     *
     * @param $users - array of user records.
     *
     * @return array
     *
     */
    public function pres( $users ) {
        $new_users = [];
        if ( empty($users) ) return $new_users;
        foreach( $users as $user ) {
            $new_users[] = user()->reset( $user )->pre();
        }
        return $new_users;
    }

    /**
     * It returns (safe) user name. Meaning it will return something meaningful if there is no name for the user.
     *
     *
     * @return string
     */
    public function getSafeName()
    {
        if ( empty( $this->name ) ) return $this->id;
        else return $this->name;
    }


}
