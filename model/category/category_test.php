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
        $this->tree_test();
    }

    public function crud() {

        $forum_category_idx = $this->createCategory( [
            'session_id' => $this->getAdminSessionId(),
            'id' => 'forum',
            'model' => 'post_config',
            'model_idx' => 1 ] );

        $re = $this->route('category.edit',
            [
                'session_id' => $this->getAdminSessionId(),
                'id'=>'forum',
                'description' => 'forum category',
                'model' => 'post_config',
                'model_idx' => 2
            ]);

        test( $re, "Category_Test::crud() " . get_error_string($re) );


        test( category('forum')->description == 'forum category', "description match");



        $child_idx = $this->createCategory( [
            'session_id' => $this->getAdminSessionId(),
            'parent_idx' => $forum_category_idx,
            'id' => 'freetalk',
            'model' => 'post_config',
            'model_idx' => 2 ] );


        $re = $this->route('category.delete', [
                'session_id' => $this->getAdminSessionId(),
                'id'=>'forum'
            ]);


        test( is_error($re) == ERROR_CATEGORY_CHILDREN_EXIST, "category childrent exist: " . get_error_string($re));


        $re = $this->route('category.delete', [
            'session_id' => $this->getAdminSessionId(),
            'id'=>'freetalk'
        ]);
        test( is_success($re), "category delete - freetalk: " . get_error_string($re));


        $re = $this->route('category.delete', [
            'session_id' => $this->getAdminSessionId(),
            'id'=>'forum'
        ]);
        test( is_success($re), "category delete - forum category: " . get_error_string($re));





    }





    public function tree_test() {

        $animal_record =  [
            'session_id' => $this->getAdminSessionId(),
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


        $animals = [];

        $record = $animal_record;
        $record['id'] = 'cat';
        $record['parent_idx'] = $animal_idx;
        $cat_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'dog';
        $record['parent_idx'] = $animal_idx;
        $dog_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'pig';
        $record['parent_idx'] = $animal_idx;
        $pig_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'landlacer';
        $record['parent_idx'] = $pig_idx;
        $landlacer_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'child-landlacer';
        $record['parent_idx'] = $landlacer_idx;
        $child_idx_of_landlacer = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'duck';
        $record['parent_idx'] = $animal_idx;
        $duck_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'wonang';
        $record['parent_idx'] = $duck_idx;
        $wonang_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'runner';
        $record['parent_idx'] = $landlacer_idx;
        $runner_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'better';
        $record['parent_idx'] = $runner_idx;
        $better_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'bester';
        $record['parent_idx'] = $better_idx;
        $bester_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'master';
        $record['parent_idx'] = $bester_idx;
        $master_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'hero';
        $record['parent_idx'] = $master_idx;
        $hero_idx = $this->createCategory( $record, true );




        $record = $animal_record;
        $record['id'] = 'whiter';
        $record['parent_idx'] = $landlacer_idx;
        $whiter_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'brighter';
        $record['parent_idx'] = $whiter_idx;
        $brighter_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'hampsher';
        $record['parent_idx'] = $pig_idx;
        $hampsher_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'doberman';
        $record['parent_idx'] = $dog_idx;
        $doberman_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'bulldog';
        $record['parent_idx'] = $dog_idx;
        $bulldog_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'hotdog';
        $record['parent_idx'] = $bulldog_idx;
        $hot_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'shepard';
        $record['parent_idx'] = $dog_idx;
        $shepard_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'majesty';
        $record['parent_idx'] = $hero_idx;
        $majesty_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'superman';
        $record['parent_idx'] = $hero_idx;
        $superman_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'superwoman';
        $record['parent_idx'] = $hero_idx;
        $superwoman_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'betman';
        $record['parent_idx'] = $hero_idx;
        $betman_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'gongja';
        $record['parent_idx'] = $master_idx;
        $gongja_idx = $this->createCategory( $record, true );

        $record = $animal_record;
        $record['id'] = 'student';
        $record['parent_idx'] = $gongja_idx;
        $student_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'hong';
        $record['parent_idx'] = $hero_idx;
        $hong_idx = $this->createCategory( $record, true );


        $record = $animal_record;
        $record['id'] = 'josu';
        $record['parent_idx'] = $betman_idx;
        $josu_idx = $this->createCategory( $record, true );



        $record = $animal_record;
        $record['id'] = 'white';
        $record['parent_idx'] = $cat_idx;
        $white_idx = $this->createCategory( $record, true );




        $animals = [
            'cat',
                'white',
            'dog',
                'doberman',
                'bulldog',
                    'hotdog',
                'shepard',
            'pig',
                'landlacer',
                    'child-landlacer',
                    'runner',
                        'better',
                            'bester',
                                'master',
                                    'hero',
                                        'majesty',
                                        'superman',
                                        'superwoman',
                                        'betman',
                                            'josu',
                                        'hong',
                                    'gongja',
                                        'student',
                    'whiter',
                        'brighter',
                'hampsher',
            'duck',
                'wonang' ];

        $categories = category()->loadChildren( $animal_idx );

        $i = 0;
        foreach ( $animals as $id ) {
            $category = $categories[$i];

            if ( $id == $category->id ) {

            }
            else {
                break;
            }
            $i++;
        }

        test( $i == count($animals), "category tree match: $id");




        /// parents test
        $parents = category()->loadParents( $student_idx, true );
        $student_parents = [ 'student', 'gongja', 'master', 'bester', 'better', 'runner', 'landlacer', 'pig', 'animal'];
        for( $i=0; $i < count($student_parents); $i++ ) {
            $id = $student_parents[ $i ];
            $parent = $parents[ $i ];
            if ( $id != $parent->id ) break;
        }
        test( $i == count($parents), "category parents match: $id");

        /// brothers test
        $brothers = category()->loadBrothers( $hero_idx );
        $hero_brothers = [
            'majesty',
            'superman',
            'superwoman',
            'betman',
            'hong'];
        for( $i=0; $i < count($hero_brothers); $i++ ) {
            $id = $hero_brothers[ $i ];
            $brother = $brothers[ $i ];
            if ( $id != $brother->id ) break;
        }
        test( $i == count($brothers), "category brother match: $id");


    }
}
