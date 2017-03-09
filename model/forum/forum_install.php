<?php
$table= 'forum_config';
db()
    ->dropTable( $table )
    ->createTable( $table )
    ->add('id', 'varchar', 64)
    ->add('name', 'varchar', 128)
    ->add('description', 'LONGTEXT')
    ->add('level_list', 'TINYINT')
    ->add('level_view', 'TINYINT')
    ->add('level_write', 'TINYINT')
    ->add('level_comment', 'TINYINT')
    ->unique( 'id');








$table= 'forum_post';
db()
    ->dropTable( $table )
    ->createTable( $table )
    ->add('idx_user', 'INT UNSIGNED DEFAULT 0')
    ->add('idx_config', 'INT UNSIGNED DEFAULT 0')
    ->add('idx_parent', 'INT UNSIGNED DEFAULT 0')
    ->add('title', 'varchar', 256)
    ->add('content', 'LONGTEXT')
    ->index( 'idx_user' )
    ->index( 'idx_config' )
    ->index( 'idx_config,idx_user' );

