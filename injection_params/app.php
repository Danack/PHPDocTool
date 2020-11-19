<?php

declare(strict_types = 1);

use PHPDocTool\InjectionParams;

function injectionParams()
{
    // These classes will only be created once by the injector.
    $shares = [
        \Redis::class,
        \Twig_Environment::class,
        \Auryn\Injector::class,
        Doctrine\ORM\EntityManager::class
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
        PHPDocTool\Repo\StatusStorage\StatusStorage::class =>
            PHPDocTool\Repo\StatusStorage\RedisStatusStorage::class,

        \PHPDocTool\Repo\FullRebuildStatus\FullRebuildStatus::class =>
            \PHPDocTool\Repo\FullRebuildStatus\RedisFullRebuildStatus::class,


//        \PHPDocTool\Repo\DocsToBuild\DocsToBuild::class =>
//            \PHPDocTool\Repo\DocsToBuild\RedisDocsToBuild::class,
//
//        \PHPDocTool\Repo\FileLastModifiedBuildTime\FileLastModifiedBuildTime::class =>
//            \PHPDocTool\Repo\FileLastModifiedBuildTime\RedisFileLastModifiedBuildTime::class,
    ];

    // Delegate the creation of types to callables.
    $delegates = [
        \Redis::class => 'createRedis',
        \Twig\Environment::class => 'createTwigForApp',
        \SlimAuryn\Routes::class => 'createRoutesForApp',

        \SlimAuryn\ExceptionMiddleware::class => 'createExceptionMiddleware',
        \SlimAuryn\SlimAurynInvokerFactory::class => 'createSlimAurynInvokerFactory',

        \Slim\Container::class => 'createSlimContainer',
        \Slim\App::class => 'createSlimAppForApp',
    ];

//    if (getConfig(['example', 'direct_sending_no_queue'], false) === true) {
//
//    }

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


