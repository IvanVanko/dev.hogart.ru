<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once 'orm.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = App::getEntityManager();;

return ConsoleRunner::createHelperSet($entityManager);