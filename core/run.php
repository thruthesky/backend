<?php



// http://localhost/www/backend-0.2/index.php?action=install

////// load routes

route()->loadRoutes();


////// check route exists

(new \model\system\System())->run();

