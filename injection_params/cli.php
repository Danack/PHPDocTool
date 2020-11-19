<?php

use PHPDocTool\InjectionParams;

if (function_exists('injectionParams') == false) {

    function injectionParams() : InjectionParams
    {
        // These classes will only be created once by the injector.
        $shares = [
        ];

        // Alias interfaces (or classes) to the actual types that should be used
        // where they are required.
        $aliases = [
            PHPDocTool\Repo\StatusStorage\StatusStorage::class =>
            PHPDocTool\Repo\StatusStorage\RedisStatusStorage::class,

            \PHPDocTool\Repo\FullRebuildStatus\FullRebuildStatus::class =>
            \PHPDocTool\Repo\FullRebuildStatus\RedisFullRebuildStatus::class,


//            \PHPDocTool\Repo\DocsToBuild\DocsToBuild::class =>
//            \PHPDocTool\Repo\DocsToBuild\RedisDocsToBuild::class,

//            \PHPDocTool\Repo\FileLastModifiedBuildTime\FileLastModifiedBuildTime::class =>
//            \PHPDocTool\Repo\FileLastModifiedBuildTime\RedisFileLastModifiedBuildTime::class,
        ];



        // Delegate the creation of types to callables.
        $delegates = [
//            \PDO::class => 'createPDO',
            \Redis::class => 'createRedis',
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
}


return injectionParams();
