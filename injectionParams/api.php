<?php

use AurynConfig\InjectionParams;

function injectionParams() : InjectionParams
{
    // These classes will only be created once by the injector.
    $shares = [
//        \SlimSession\Helper::class,
        \Auryn\Injector::class,
        \Doctrine\ORM\EntityManager::class,
        \Airbrake\Notifier::class
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
        \VarMap\VarMap::class => \VarMap\Psr7InputMapWithVarMap::class,
        \Params\Input::class => \Example\Psr7Input::class,
    ];

    // Delegate the creation of types to callables.
    $delegates = [
        \Psr\Log\LoggerInterface::class => 'createLogger',
        \PDO::class => 'createPDO',
        \Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',
        \Redis::class => 'createRedis',
        \Slim\App::class => 'createAppForApi',
        \SlimAuryn\Routes::class => 'createRoutesForApi',

        \SlimAuryn\SlimAurynInvokerFactory::class => 'createSlimAurynInvokerFactory',
        \SlimAuryn\ExceptionMiddleware::class => 'createExceptionMiddleware',
    ];

    // Define some params that can be injected purely by name.
    $params = [];

    $prepares = [
    ];

    $defines = [];

    $injectionParams = new InjectionParams(
        $shares,
        $aliases,
        $delegates,
        $params,
        $prepares,
        $defines
    );

    return $injectionParams;
}
