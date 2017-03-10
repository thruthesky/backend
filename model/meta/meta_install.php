<?php

$_table_name = DATABASE_PREFIX . 'meta';


db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('model', 'varchar', 32)
    ->add('model_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('code', 'varchar', 32)
    ->add('data', 'LONGTEXT')
    ->unique('model,model_idx,code');


die_if_table_not_exist( $_table_name );