<?php

require_once __DIR__.'/../vendor/autoload.php';

use Pinboard\Logger\DbalLogger;
use Pinboard\Stopwatch\Stopwatch;

$app = new Silex\Application();
$app['params'] = Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__.'/../config/parameters.yml'));
date_default_timezone_set($app['params']['timezone']);

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    if (!isset($app['params']['base_url']) || empty($app['params']['base_url'])) {
        $baseUrl = '/';
    } else {
        $baseUrl = $app['params']['base_url'];
    }

    if (substr($baseUrl, -1) != '/') {
        $baseUrl .= '/';
    }
    $twig->addGlobal('base_url', $baseUrl);

    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$dbOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => $app['params']['db']['name'],
    'host' => $app['params']['db']['host'],
    'user' => $app['params']['db']['user'],
    'password' => $app['params']['db']['pass'],
);
if (isset($app['params']['db']['port'])) {
    $dbOptions['port'] = $app['params']['db']['port'];
}
if (isset($app['params']['db']['unix_socket'])) {
    $dbOptions['unix_socket'] = $app['params']['db']['unix_socket'];
}

$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => $dbOptions));
$app['dbs.config']['default']->setSQLLogger(new DbalLogger(new Stopwatch(), $app['params']['db']['host']));

//query caching
$cacheClassName =
    'Doctrine\\Common\\Cache\\'.
    (isset($app['params']['cache']) ?
        Doctrine\Common\Util\Inflector::classify($app['params']['cache']) :
        'Array'
    ).
    'Cache'
    ;

$app['db']->getConfiguration()->setResultCacheImpl(new $cacheClassName());

$users = array();
if (isset($app['params']['secure']['users'])) {
    foreach ($app['params']['secure']['users'] as $userName => $userData) {
        $users[$userName] = array(
                'ROLE_USER',
                $userData['password'],
            );
    }
}

if (isset($app['params']['secure']['enable']) && $app['params']['secure']['enable']) {
    $app->register(new Silex\Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
            'secure_area' => array(
                'pattern' => '^/',
                'http' => true,
                'users' => $users,
            ),
        ),
    ));
}
