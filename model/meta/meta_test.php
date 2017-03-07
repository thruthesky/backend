<?php

namespace model\meta;

class Meta_Test {
    public function __construct()
    {
    }
    public function run() {

        $this->create();
/*
        $idx = meta()->set('abc', 123, 'code-unit-test', 'data');
        test( $idx, "Meta::set() code: code, data: data re: idx: $idx");

//
        $another_idx = meta()->set('abc', 111, 'code-unit-test', 'another idx');
        test( $another_idx, "meta::set() another idx is okay");

        $new_idx = meta()->set('abc', 123, 'code-unit-test', 'new data');
        test( $new_idx, "Meta::set() code: code, data: new data re: new_idx: $new_idx");

        test( $idx != $new_idx, "Meta::set() new insert. data=new data idx: $idx, new_idx: $new_idx");


        $count = meta()->getCount( 'abc', 123, 'code-unit-test' );
        test( $count == 1, "Meta abc, 123, code-unit-test has only 1 record as it should.");


        meta()->delete( 'abc', 111, 'code-unit-test' );
        $count = meta()->getCount( 'abc', 111, 'code-unit-test');
        test( $count == 0, "Meta abc, 111, code-unit-test has deleted.");
    */
    }
    public function create() {

        $model = "meta-test-1";
        $model_idx = 123;
        $code = 'the code';
        $data = 'the data';

        $meta = meta()->load( $model, $model_idx, $code );
        if ( $meta->exist() ) {
            $meta->delete();
        }



        $idx = meta()
            ->set('model', $model)
            ->set('model_idx', $model_idx)
            ->set('code', $code)
            ->set('data', $data)
            ->create();



        test( $idx, "Meta create: model: $model. idx: $idx");

        if ( is_success( $idx ) ) {

            $meta = meta()->load( $idx );
            test( $meta->model == $model, "Meta model was successfully created");

            meta()->load( $idx )->delete();
            test( ! meta()->load( $idx )->exist(), "Meta deleted" );

        }


    }
}

