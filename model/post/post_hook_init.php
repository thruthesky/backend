<?php
/**
 * @see README.md#File Delete
 */
hook()->add('route.file.delete', function( $vars ) {
    debug_log("HOOK: route.file.delete begins.");
    debug_log($vars);
    $file = $vars['run'];

    /**
     * If the file is not 'finish'ed, then just return without password match.
     *
     *
     */
    if ( $file->finish != 'Y' ) return;

    /**
     * If the file is finished
     */
    if ( $file->user_idx == anonymousUser()->idx ) {
        if ( $file->model == 'post_data' ) { // the file is for post
            if ( ! in('password') ) { // if not password provided, error.
                error( ERROR_PASSWORD_REQUIRED_FOR_ANONYMOUS );
                if ( is_test() ) return;
                else exit();
            }
            $post = post( $file->model_idx );
            if ( is_error( $post ) ) exit( error( $post ) );
            if ( $re = $post->editPermission() ) exit( error( $re ) ); // if password does match, error.

        }
    }
});


