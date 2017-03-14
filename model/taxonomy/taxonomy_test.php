<?php
namespace model\taxonomy;
class Taxonomy_Test extends \model\test\Test {
    public function run() {

        $re = taxonomy()->search( [] );
        test( is_error( $re ) == ERROR_TABLE_NOT_SET, "table not set error" );
        $re = taxonomy()->setTable('meta')->search( [] );
        test( is_success( $re ), "taxonomy search with empty options: " . get_error_string($re));

        $re = taxonomy()->setTable('meta')->search( ['where' => 'idx > 22'] );
        test( is_error( $re ) == ERROR_MISSING_BINDING_MARK, "Query without binding mark(?) is an error. " . get_error_string($re));
    }
}

