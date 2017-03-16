<?php

$_table_name = DATABASE_PREFIX . 'file';


db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('model', 'varchar', 32)
    ->add('model_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('user_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('code','varchar', 128)
    ->add('name', 'varchar', 255)
    ->add('type', 'varchar', 64)
    ->add('size', 'INT UNSIGNED DEFAULT 0')
    ->add('finish',"char(1) DEFAULT 'N'")
    ->add('no_of_download','INT UNSIGNED DEFAULT 0')
    ->index('model,model_idx,code')
    ->index('model,code');




die_if_table_not_exist( $_table_name );