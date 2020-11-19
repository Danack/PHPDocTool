<?php

use Auryn\Injector;

require_once(__DIR__.'/../vendor/autoload.php');
require_once __DIR__ . '/../injection_params/cli_test.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/factories.php';

/**
 * @param array $testAliases
 * @return \Auryn\Injector
 */
function createInjector($testDoubles = [], $testAliases = [])
{
    $injectionParams = injectionParams(
        $testDoubles,
        $testAliases
    );

    $injector = new \Auryn\Injector();
    $injectionParams->addToInjector($injector);

    $injector->share($injector); //Yolo ServiceLocator
    return $injector;
}
