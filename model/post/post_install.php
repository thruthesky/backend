<?php

create_post_config_table( DATABASE_PREFIX . 'post_config' );

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




create_post_data_table( DATABASE_PREFIX . 'post_data' );
create_post_data_table( DATABASE_PREFIX . 'post_data_deleted' );


function create_post_data_table( $_table_name ) {
    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('user_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('post_config_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('parent_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('password', 'varchar')
        ->add('secret', 'char', 1)
        ->add('deleted', 'char', 1)
        ->add('title', 'varchar', 256)
        ->add('content', 'LONGTEXT')

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