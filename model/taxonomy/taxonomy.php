<?php
namespace model\taxonomy;
class Taxonomy extends \model\base\Base  {

    private $table = null;
    public function __construct()
    {
        parent::__construct();

    }
    public function setTable( $table ) {
        $this->table = $table;
    }

    public function getTable() {
        return $this->table;
    }






}