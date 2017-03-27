<?php

namespace model\category;

class Category_Test extends \model\test\Test
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {

        $this->crud();
    }

    public function crud() {

        $animal_record =  [
            'id' => 'animal',
            'description' => "animal category",
            'model' => 'post_config',
            'model_idx' => 1
        ];
        $re = $this->route('category.create', $animal_record);

        if ( is_error($re) == ERROR_DATABASE_UNIQUE_KEY ) {
            category('animal')->delete();
            $re = $this->route('category.create', $animal_record);
        }

        test( is_success($re), "create animal root category: " . get_error_string($re));
        $animal_idx = $re['data']['idx'];


        $record = $animal_record;
        $record['id'] = 'cat';
        $record['parent_idx'] = $animal_idx;
        $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'dog';
        $record['parent_idx'] = $animal_idx;
        $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'pig';
        $record['parent_idx'] = $animal_idx;
        $pig_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'landlacer';
        $record['parent_idx'] = $pig_idx;
        $pig_idx = $this->createCategory( $record, true );


        di ( category()->getChildren( $animal_idx ) );



    }
}
