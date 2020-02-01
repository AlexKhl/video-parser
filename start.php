<?php

require __DIR__ . '/vendor/autoload.php';

use \Artec3D\Dispatcher as Dispatcher;

//$url = 'https://stroychik.ru/tools/kak-pravilno-varit-svarkoj';
$url = 'https://aqua-rmnt.com/uchebnik/svarka/kak-pravilno-varit-elektrosvarkoj.html';
//$url = 'https://stanok.guru/metalloobrabotka/svarka/kak-pravilno-varit-svarkoy-instrukciya-i-video-urok.html';

$dispatcher = new Dispatcher($url);
$dispatcher->handle();