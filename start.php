<?php

require __DIR__ . '/vendor/autoload.php';

use \Artec3D\Dispatcher as Dispatcher;

$dispatcher = new Dispatcher($argv[1]);
$dispatcher->handle();