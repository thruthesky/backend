<?php
/**
 *
 */
namespace model\post;
class Post extends \model\entity\Entity
{
    public function __construct()
    {
        parent::__construct();
    }



    /**
     *
     *
     * Return TRUE if the password matches.
     *
     *
     * @Overrides entity()checkPassword()
     *
     * @param $plain_text_password
     * @param $_
     * @return bool
     */
    public function checkPassword($plain_text_password, $_ = null )
    {
        return parent::checkPassword($plain_text_password, $this->password);
    }



    /**
     *
     * Return 'OK' if the permission OK.
     *
     *
     * @Attention all post/comment must use this function.
     *
     * @return int
     */
    public function editPermission() {
        if ( ! $this->exist() ) return ERROR_POST_NOT_EXIST;

        $config = config()->load( $this->post_config_idx );

        if ( currentUser()->isAdmin() ) return OK;
        else if ( currentUser()->isAnonymous() ) {
            if ( $this->user_idx != currentUser()->idx ) return ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS;
            if ( empty( in('password') ) ) return ERROR_PASSWORD_EMPTY;
            if ( $this->checkPassword( in('password') ) ) return OK;
            else return ERROR_WRONG_PASSWORD;
        }
        else if ( currentUser()->idx == $this->user_idx ) return OK;
        else return ERROR_NOT_YOUR_POST_DATA;

    }

    public function deletePermission() {

        if ( ! $this->exist() ) return ERROR_POST_NOT_EXIST;

        $config = config()->load( $this->post_config_idx );


        if ( currentUser()->isAdmin() ) return OK;

        if ( currentUser()->isAnonymous() ) {
            if ( $this->user_idx != currentUser()->idx ) return ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS;
            if ( empty( in('password') ) ) return ERROR_PASSWORD_EMPTY;
            if ( $this->checkPassword( in('password') ) ) return OK;
            else return ERROR_WRONG_PASSWORD;

        }
        if ( $this->user_idx == currentUser()->idx ) return OK;
        return ERROR_NOT_YOUR_POST_DATA;

    }



    public function deleted() {
        return $this->deleted;
    }


    /**
     *
     *
     *
     * @param Post_Data|Post_Comment $post
     * @param $config
     *
     * @return mixed
     */
    public function vote_interface( $post, Post_Config $config ) {

        if ( $post->user_idx == currentUser()->idx ) return error( ERROR_CANNOT_VOTE_YOUR_OWN_POST );

        $vote = post_vote( $post->idx, currentUser()->idx );
        if ( $vote->exist() ) return error( ERROR_ALREADY_VOTE );


        post_vote()
            ->set('post_idx', $post->idx)
            ->set('user_idx', currentUser()->idx)
            ->create();


        if ( in('choice') == 'G' ) $post->update([ 'vote_good' => $post->vote_good + 1 ]);
        else if ( in('choice') == 'B' ) $post->update([ 'vote_bad' => $post->vote_bad + 1 ]);

        $reloaded = post( $post->idx );

        return success( [
            'idx' => $reloaded->idx,
            'vote_good' => $reloaded->vote_good,
            'vote_bad' => $reloaded->vote_bad
        ] );

    }




    /**
     * @param Post_Data|Post_Comment $post
     * @param $config
     * @return mixed
     */
    public function report_interface( $post, Post_Config $config ) {

        $report = post_report( $post->idx, currentUser()->idx );
        if ( $report->exist() ) return error( ERROR_ALREADY_REPORT );

        post_report()
            ->set('post_idx', $post->idx)
            ->set('user_idx', currentUser()->idx)
            ->create();

        $post->update([
            'report' => $post->report + 1
        ]);
        $reloaded = post( $post->idx );



        return success( [
            'idx' => $reloaded->idx,
            'report' => $reloaded->report
        ] );
    }



}