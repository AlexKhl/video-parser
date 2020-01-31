<?php

require __DIR__ . '/vendor/autoload.php';

use \Artec3D\Dispatcher as Dispatcher;

$url = 'https://stroychik.ru/tools/kak-pravilno-varit-svarkoj';

$dispatcher = new Dispatcher($url);
$dispatcher->handle();