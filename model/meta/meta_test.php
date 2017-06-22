<?php

namespace model\meta;

use model\test\Test;

class Meta_Test extends Test {
    public function __construct()
    {
    }
    public function run() {

        $this->create();
        $this->crudRoute();
        $this->multi_create();


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


        /*
        $meta = meta()->load( $model, $model_idx, $code );
        if ( $meta->exist() ) {
            $meta->delete();
        }
        */


        $idx = meta()
            ->set('model', $model)
            ->set('model_idx', $model_idx)
            ->set('code', $code)
            ->set('data', $data)
            ->create();

        test( is_success($idx), "Meta create: model: $model. idx: $idx. " . get_error_string( $idx ));

        if ( is_success( $idx ) ) {

            $meta = meta()->load( $idx );
            test( $meta->model == $model, "Meta model was successfully created");

            meta()->load( $idx )->delete();
            test( ! meta()->load( $idx )->exist(), "Meta deleted" );

        }




    }

    public function crudRoute() {



        //////
        //////      Creating meta data through routes.
        //////
        $re = $this->route('meta.create', []);
        test( is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, "session_id missing: " . get_error_string($re));

        $session_id_thruthesky = thruthesky()->getSessionId();
        $params = [
            'session_id' => $session_id_thruthesky
        ];
        $re = $this->route('meta.create', $params);
        test( is_error($re) == ERROR_MODEL_IS_EMPTY, 'model is empty: ' . get_error_string($re));

        $params['model'] = 'clothes';
        $re = $this->route('meta.create', $params);
        test( is_error($re) == ERROR_MODEL_IDX_IS_EMPTY, 'model is empty: ' . get_error_string($re));

        $params['model_idx'] = 1;
        $re = $this->route('meta.create', $params);
        test( is_error($re) == ERROR_CODE_IS_EMPTY, 'code is empty: ' . get_error_string($re));

        $params['code'] = 'mango';
        $re = $this->route('meta.create', $params);
        if ( is_success($re) ) {
		test( 1, "mango clothes has created: width: session_id: $params[session_id] " . $re['data']['meta']['idx']);
	}
        else test( 0, 'mango clothes meta creation failed: ' . get_error_string($re) );


        $params['code'] = 'marks';
        $re = $this->route('meta.create', $params);
        if ( is_success($re) ) {
		test( 1, 'marks clothes has created: ' . $re['data']['meta']['idx']);
	}
        else test( 0, 'marks clothes meta creation failed: ' . get_error_string($re) );


        ///
        ///     Search
        ///
        $params = [];
        $params['where'] = 'model=? AND model_idx=?';
        $params['bind'] = "clothes,1";
        $re = $this->route("meta.list", $params);
        test( $re['data']['total'] == 0, "no data");

        $params['session_id'] = anonymousUser()->getSessionId();
        $re = $this->route("meta.list", $params);
        test( $re['data']['total'] == 0, "no data for anonymous");


        $params['session_id'] = $session_id_thruthesky;
        $re = $this->route("meta.list", $params);
        test( $re['data']['total'] == 2, "two records for thruthesky: re: total: {$re['data']['total']}");
        test( $re['data']['meta'][0]['code'] == 'mango', "mango meta");

//        di($params);
//        di($re);
//        exit;


        $meta = $re['data']['meta'];

        /// delete

        $params = [];
        $re = $this->route("meta.delete", $params);
        test( is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, "empty session id");

        $params['session_id'] = testUser()->getSessionId();
        $re = $this->route('meta.delete', $params);
        test( is_error($re) == ERROR_REQUIRED_INPUT_IS_MISSING, "missing idx");


        $params['idx'] = $meta[0]['idx'];
        $re = $this->route('meta.delete', $params);
        test( is_error($re) == ERROR_NOT_YOUR_META, "not your data.");


        $params['session_id'] = thruthesky()->getSessionId();
        $re = $this->route('meta.delete', $params);
        if ( is_success($re) ) {
            test( $re['data']['idx'] == $meta[0]['idx'], "deleted");
        }
        else test(0, "delete failed: " . get_error_string($re));


        $params['idx'] = $meta[1]['idx'];
        $re = $this->route('meta.delete', $params);
        if ( is_success($re) ) {
            test( $re['data']['idx'] == $meta[1]['idx'], "deleted");
        }
        else test(0, "delete failed: " . get_error_string($re));


        ///

        $params = [];
        $params['where'] = 'model=? AND model_idx=?';
        $params['bind'] = "clothes,1";
        $params['session_id'] = thruthesky()->getSessionId();
        $re = $this->route("meta.list", $params);
        test( $re['data']['total'] == 0, "no data");



    }

    public function multi_create() {

        $re = meta()->create();         // error test. data is no data to create.
        test( is_error($re), "Multi meta creation: " . get_error_string($re));

        $metas = [
            "name" => "JaeHo",
            "age" => 44,
            "gender" => "M"
        ];

        // create multi meta data.
        $re = meta()->create('multi', 111, $metas);
        test( is_success($re), "meta multi create ok");


        $rows = meta()->get( 'multi', 111 );                   // get multi data
        // di($rows);
        test( $rows['age'] == $metas['age'], "Age check ok");   // check


        // edit one of the meta data
        meta()->set('model', 'multi')->set('model_idx', 111)->set('code', 'age')->set('data', 33)->create();
        $rows = meta()->get( 'multi', 111 );                   // get multi data
        test( $rows['age'] == 33, "Age check ok"); // check.

    }
}

