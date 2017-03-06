<?php


db()
    ->dropTable( 'meta' )
    ->createTable( 'meta' )
    ->add('model', 'varchar', 32)
    ->add('model_idx', 'INT UNSIGNED DEFAULT 0')
    ->add('code', 'varchar', 32)
    ->add('data', 'LONGTEXT')
    ->unique('model,model_idx,code');


