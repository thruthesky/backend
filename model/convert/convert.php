<?php
namespace model\convert;
class Convert extends \model\entity\Entity {
    public function run() {




        $rows = db()->rows("select idx,id from member");
        print_r($rows);
    }
}