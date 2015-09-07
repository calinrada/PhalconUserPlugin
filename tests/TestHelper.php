<?php

use Phalcon\DI;
use Phalcon\DI\FactoryDefault;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);
define('LIBRARY_PATH', __DIR__.'/../lib/');

set_include_path(
    ROOT_PATH.PATH_SEPARATOR.get_include_path()
);

// required for phalcon/incubator
include __DIR__.'/../vendor/autoload.php';

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    ROOT_PATH,
    LIBRARY_PATH,
));

$loader->registerNamespaces(array(
    'Phalcon\UserPlugin' => LIBRARY_PATH,
));

$loader->register();

$di = new FactoryDefault();
//DI::reset();

// add any needed services to the DI here

DI::setDefault($di);
