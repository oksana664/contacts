<?php

$router = $di->getRouter();

$router->removeExtraSlashes(true);
$router->add('/', ['controller' => 'contacts', 'action' => 'search']);
$router->add('/contacts', ['controller' => 'contacts', 'action' => 'search']);

$router->handle();
