<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;

// Required for phalcon/incubator
include __DIR__ . "/../vendor/autoload.php";

set_include_path(
    __DIR__ . PATH_SEPARATOR . get_include_path()
);

$di = new FactoryDefault();
Di::reset();

// Add any needed services to the DI here

Di::setDefault($di);
