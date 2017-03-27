<?php
/**
 * @see README.md
 */
namespace model\category;


use model\entity\Entity;

class Category extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('category');
    }




}
