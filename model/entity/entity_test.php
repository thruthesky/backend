<?php
/**
 *
 *
 *
 */
namespace model\entity;
use model\meta\Meta;

class Entity_Test extends \model\test\Test {

    public function single_test() {

        parent::$reload = 2;
        $this->run();
    }


    public function run() {

        $this->basic();
        $this->multi_load();

    }

    public function basic() {


        $entity = entity();

        $entity->a = 'apple'; // setter
        test( $entity->a == 'apple', "Entity property test"); // getter



        test( $entity->a == $entity->getRecord()['a'], "Entity getter and record test");



        $b = $entity->set('b', 'banana')    // set
        ->getRecord()['b'];             // get

        test( $entity->b == $b, "Entity setter, getter, record tdst");




        test( $entity->exist() === FALSE, "Entity does not exist");

        $entity->idx = 1;
        test( $entity->exist(), "Entity exists");




        /// Create an entity.
        $entity->setTable('meta');
        $entity->reset( [] );
        $idx = $entity->set('model', 'model-1')->create();
        test( is_success($idx), "Entity created: $idx");



        /// Load and entity
        $e = entity()->setTable('meta')->load($idx); // since entity has no table,
        test( is_success( $e ), "meta load: " . get_error_string($e));
        test( $e->exist(), "Entity load: $idx");


        $copy = entity()->setTable('meta')->loadQuery("idx=$idx");
        test( $e->model == $copy->model, "Entity loadQuery : {$copy->model}");


        // Update

        $re = $copy->update( ['model' =>'entity new model test', 'data'=>'model data'] ); // it reloads.


        test( is_success( $re ), "Entity update: " . get_error_string($re));

        // Check
        test( $copy->data == 'model data', "Update Ok");

        // Load and Check
        $loadAgain = entity()->setTable('meta')->load( $idx );
        test( $loadAgain->data == 'model data', 'Reload data check');

        $copy->delete();


    }


    public function multi_load() {

        $idxes = [];

        $idx = meta()
            ->set('model', 'abc')
            ->set('model_idx', 124)
            ->set('code', 'a')
            ->set('data', 'data')
            ->create();
        $idxes[] = $idx;

        $idx = meta()
            ->set('model', 'abc')
            ->set('model_idx', 124)
            ->set('code', 'b')
            ->set('data', 'data')
            ->create();
        $idxes[] = $idx;



        $idx = meta()
            ->set('model', 'abc')
            ->set('model_idx', 124)
            ->set('code', 'c')
            ->set('data', 'data')
            ->create();
        $idxes[] = $idx;

        $meta = meta()->load( $idx );

        test( $meta instanceof Entity, "meta is instance of Entity");
        test( $meta instanceof Meta, "meta is instance of Meta");


        $metas = meta()->loads( $idxes );

        test( $metas, "meta()->loads() " . get_error_string($metas) );
        test( $metas[0] instanceof Meta, "metas[0] is an instance of Meta" );

        $loads = meta()->loadsQuery("model='abc' and model_idx=124");

        array_walk( $metas, function( $v, $index ) use ( $loads ) {
            test( $v == $loads[$index], "meta[$index] equal");
            test( $v->code == $loads[$index]->code, "meta[$index] equal: code = {$v->code}");
        });

    }
}

