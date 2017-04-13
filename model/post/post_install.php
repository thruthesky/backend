<?php

create_post_config_table( DATABASE_PREFIX . 'post_config' );


create_post_data_table( DATABASE_PREFIX . 'post_data' );
create_post_data_table( DATABASE_PREFIX . 'post_data_deleted' );

create_post_vote_table( DATABASE_PREFIX . 'post_vote' );
create_post_report_table( DATABASE_PREFIX . 'post_report' );


config()->set('id', 'test')->create();




function create_post_config_table( $_table_name ) {

    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('id', "VARCHAR(64) DEFAULT ''")
        ->add('name', 'VARCHAR', 128)
        ->add('description', 'LONGTEXT')
        ->add('moderators', 'LONGTEXT')
        ->add('level_list', 'TINYINT')
        ->add('level_view', 'TINYINT')
        ->add('level_write', 'TINYINT')
        ->add('level_comment', 'TINYINT')
        ->add('deleted', 'char', 1)
        ->unique( 'id');
    die_if_table_not_exist( $_table_name );

}





function create_post_data_table( $_table_name ) {
    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('root_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('parent_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('order_no', 'INT UNSIGNED DEFAULT 0')
        ->add('depth', 'INT UNSIGNED DEFAULT 0')
        ->add('user_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('post_config_idx', 'INT UNSIGNED DEFAULT 0')


        ->add('first_image_idx', 'INT UNSIGNED DEFAULT 0')

        ->add('vote_good', 'INT UNSIGNED DEFAULT 0')
        ->add('vote_bad', 'INT UNSIGNED DEFAULT 0')
        ->add('report', 'INT UNSIGNED DEFAULT 0')

        ->add('content', 'LONGTEXT')
        ->add('password', 'varchar')
        ->add('secret', 'char', 1)
        ->add('deleted', 'char', 1)
        ->add('title', 'varchar', 256)

        ->add('ip', 'varchar', 32)
        ->add('user_agent', 'varchar', 255)

        ->add('name', 'VARCHAR', 254)
        ->add('middle_name', 'VARCHAR', 254)
        ->add('last_name', 'VARCHAR', 254)
        ->add('email', 'VARCHAR', 254)
        ->add('gender', 'VARCHAR', 254)
        ->add('birthdate', 'INT UNSIGNED DEFAULT 0')
        ->add('mobile', 'VARCHAR', 254)
        ->add('landline', 'VARCHAR', 254)
        ->add('contact', 'VARCHAR', 254)

        ->add('country', 'VARCHAR', 64)
        ->add('province', 'VARCHAR', 256)
        ->add('city', 'VARCHAR', 256)
        ->add('address', 'VARCHAR', 256)
        ->index( 'user_idx' )
        ->index( 'post_config_idx' )
        ->index( 'post_config_idx,user_idx' );
    die_if_table_not_exist( $_table_name );
}




function create_post_vote_table( $_table_name ) {

    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('post_idx', "INT UNSIGNED DEFAULT 0")
        ->add('user_idx', "INT UNSIGNED DEFAULT 0")
        ->add('choice', 'char', 1)
        ->unique( 'post_idx,user_idx');
    die_if_table_not_exist( $_table_name );

}



function create_post_report_table( $_table_name ) {

    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('post_idx', "INT UNSIGNED DEFAULT 0")
        ->add('user_idx', "INT UNSIGNED DEFAULT 0")
        ->unique( 'post_idx,user_idx');
    die_if_table_not_exist( $_table_name );

}


