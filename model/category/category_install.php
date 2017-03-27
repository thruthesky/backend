<?php

$_table_name = DATABASE_PREFIX . 'category';


db()
    ->dropTable( $_table_name )
    ->createTable( $_table_name )
    ->add('root_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('parent_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('order_no', 'INT UNSIGNED DEFAULT 0')
    ->add('depth', 'INT UNSIGNED DEFAULT 0')
    ->add('model', 'varchar', 32)
    ->add('model_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('id', 'varchar', 64)
    ->add('name', 'varchar', 255)
    ->add('description', 'varchar', 255)
    ->unique('id')
    ->index('model,model_idx,name');


die_if_table_not_exist( $_table_name );

