<?php
/**
 *
 *
 * @todo meta()->create( [ ... ] );
 * @todo meta()->set()->set()->create();
 * @todo meta()->update( [ ... ] );
 * @todo meta()->load( ... )->set()->update()
 */
namespace model\entity;
class Entity_Test extends \model\test\Test {

    public function run() {



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

        $re = $copy->update( ['model' =>'entity new model test', 'data'=>'model data']); // it reloads.


        test( is_success( $re ), "Entity update: " . get_error_string($re));

        // Check
        test( $copy->data == 'model data', "Update Ok");

        // Load and Check
        $loadAgain = entity()->setTable('meta')->load( $idx );
        test( $loadAgain->data == 'model data', 'Reload data check');

        $copy->delete();



    }

}

