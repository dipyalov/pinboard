<?php

require __DIR__.'/../src/init.php';

$app['debug'] = $app['params']['debug'] ?? false;

include __DIR__.'/../src/Pinboard/Controller/_before.php';
$prefix = rtrim($app['params']['route_prefix'] ?? '', '/');
$app->mount("$prefix/", include __DIR__.'/../src/Pinboard/Controller/index.php');
$app->mount("$prefix/server", include __DIR__.'/../src/Pinboard/Controller/server.php');
$app->mount("$prefix/timers", include __DIR__.'/../src/Pinboard/Controller/timer.php');

$app->run();
