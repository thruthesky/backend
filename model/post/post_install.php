<?php
$_table_name = DATABASE_PREFIX . 'post_config';
db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('id', 'varchar', 64)
    ->add('name', 'varchar', 128)
    ->add('description', 'LONGTEXT')
    ->add('level_list', 'TINYINT')
    ->add('level_view', 'TINYINT')
    ->add('level_write', 'TINYINT')
    ->add('level_comment', 'TINYINT')
    ->unique( 'id');
die_if_table_not_exist( $_table_name );




$_table_name = DATABASE_PREFIX . 'post_data';
db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('idx_user', 'INT UNSIGNED DEFAULT 0')
    ->add('idx_config', 'INT UNSIGNED DEFAULT 0')
    ->add('idx_parent', 'INT UNSIGNED DEFAULT 0')
    ->add('title', 'varchar', 256)
    ->add('content', 'LONGTEXT')
    ->index( 'idx_user' )
    ->index( 'idx_config' )
    ->index( 'idx_config,idx_user' );
die_if_table_not_exist( $_table_name );