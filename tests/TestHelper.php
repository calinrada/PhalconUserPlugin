<?php

use Phalcon\Di\Di;
use Phalcon\Di\FactoryDefault;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);
define('LIBRARY_PATH', __DIR__.'/../lib/');
define('LIBRARY_PLUGIN_PATH', __DIR__.'/../lib/Plugin');
define('INCUBATOR_PATH', __DIR__.'/../incubator/'); // workaround until incubator gets updated

set_include_path(
    ROOT_PATH.PATH_SEPARATOR.get_include_path()
);

// required for phalcon/incubator
include __DIR__.'/../vendor/autoload.php';

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Autoload\Loader();

$loader->setDirectories(array(
    ROOT_PATH,
    LIBRARY_PATH,
));

$loader->setNamespaces(array(
    'Phalcon\UserPlugin' => LIBRARY_PATH,
    'Phalcon\UserPlugin\Plugin' => LIBRARY_PLUGIN_PATH,
    'Phalcon\Incubator\Test' => INCUBATOR_PATH
));

$loader->register();

$di = new FactoryDefault();
//DI::reset();

// add any needed services to the DI here

DI::setDefault($di);
