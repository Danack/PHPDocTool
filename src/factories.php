<?php

declare(strict_types=1);

use PHPDocTool\Config;
use Auryn\Injector;
use Psr\Http\Message\ResponseInterface;
use PHPDocTool\Exception\RedisConnectionException;
use Twig\TwigFunction;


function createRedis(Config $config)
{
    try {
        $redisConfig = $config->getRedisConfig();

        $redis = new Redis();
        $redis->connect($redisConfig->getHost(), $redisConfig->getPort());
        $redis->auth($redisConfig->getPassword());
        $redis->ping();

        return $redis;
    }
    catch (\ErrorException $e) {
        throw new RedisConnectionException(
            "Failed to connect to Redis",
            $e->getCode(),
            $e
        );
    }
}


function createRoutesForApp()
{
    return new \SlimAuryn\Routes(__DIR__ . '/../routes/app_routes.php');
}

function createRoutesForAdmin()
{
    return new \SlimAuryn\Routes(__DIR__ . '/../routes/admin_routes.php');
}

function createRoutesForApi()
{
    return new \SlimAuryn\Routes(__DIR__ . '/../routes/api_routes.php');
}

function createExceptionMiddlewareForApp(\Auryn\Injector $injector)
{
    $exceptionHandlers = [
        \Params\Exception\ValidationException::class => 'paramsValidationExceptionMapper'
    ];

    $resultMappers = getResultMappers($injector);

    return new \SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        $resultMappers
    );
}

function getResultMappers(\Auryn\Injector $injector)
{
    $twigResponseMapperFn = function (
        \SlimAuryn\Response\TwigResponse $twigResponse,
        ResponseInterface $originalResponse
    ) use ($injector) {
        $twigResponseMapper = $injector->make(\SlimAuryn\ResponseMapper\TwigResponseMapper::class);

        return $twigResponseMapper($twigResponse, $originalResponse);
    };

    return [
        \SlimAuryn\Response\StubResponse::class => '\SlimAuryn\ResponseMapper\ResponseMapper::mapStubResponseToPsr7',

        ResponseInterface::class => 'SlimAuryn\ResponseMapper\ResponseMapper::passThroughResponse',
        'string' => 'convertStringToHtmlResponse',
        \SlimAuryn\Response\TwigResponse::class => $twigResponseMapperFn
    ];
}

function createExceptionMiddleware(\Auryn\Injector $injector)
{
    return new SlimAuryn\ExceptionMiddleware(
        getExceptionMappers(),
        getResultMappers($injector)
    );
}

function createSlimAurynInvokerFactory(
    \Auryn\Injector $injector,
    \SlimAuryn\RouteMiddlewares $routeMiddlewares
) {
    $resultMappers = getResultMappers($injector);

    return new SlimAuryn\SlimAurynInvokerFactory(
        $injector,
        $routeMiddlewares,
        $resultMappers
    );
}

function getExceptionMappers()
{
    $exceptionHandlers = [
        \Params\Exception\ValidationException::class => 'paramsValidationExceptionMapper',
        \ParseError::class => 'parseErrorMapper',
        \PDOException::class => 'pdoExceptionMapper',
    ];

    return $exceptionHandlers;
}



function createSlimContainer()
{
    $container = new \Slim\Container();
    global $request;

    if (isset($request) && $request !== null) {
        $container['request'] = $request;
    }

    return $container;
}


function createSlimAppForApp(Injector $injector, \Slim\Container $container)
{
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = 'appErrorHandler';

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

    $app = new \Slim\App($container);

    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));
//    $app->add($injector->make(\Osf\Middleware\ContentSecurityPolicyMiddleware::class));
////    $app->add($injector->make(\Osf\Middleware\BadHeaderMiddleware::class));
//    $app->add($injector->make(\Osf\Middleware\AllowedAccessMiddleware::class));
//    $app->add($injector->make(\Osf\Middleware\MemoryCheckMiddleware::class));

    return $app;
}





function createTwigForApp(\Auryn\Injector $injector, Config $config)
{
    // The templates are included in order of priority.
    $templatePaths = [
        __DIR__ . '/../app/template'
    ];

    $twigConfig = $config->getTwigConfig();

    $loader = new Twig\Loader\FilesystemLoader($templatePaths);
    $twig = new Twig\Environment($loader, array(
        'cache' => $twigConfig->isCache(),
        'strict_variables' => true,
        'debug' => $twigConfig->isDebug()
    ));

    // Inject function - allows DI in templates.
    $function = new Twig\TwigFunction(
        'inject',
        function (string $type) use ($injector) {
            return $injector->make($type);
        }
    );
    $twig->addFunction($function);


    $rawParams = ['is_safe' => array('html')];

    $twigFunctions = [
        'renderNavbarLinks' => 'renderNavbarLinks'
    ];

    foreach ($twigFunctions as $functionName => $callable) {
        $function = new Twig\TwigFunction($functionName, function () use ($injector, $callable) {
            return $injector->execute($callable);
        });
        $twig->addFunction($function);
    }

    $function = new Twig_SimpleFunction('linkableTitle', 'linkableTitle', $rawParams);
    $twig->addFunction($function);

    $rawTwigFunctions = [
        'memory_debug' => 'memory_debug',
        'request_nonce' => 'request_nonce',
    ];

    foreach ($rawTwigFunctions as $functionName => $callable) {
        $function = new Twig\TwigFunction($functionName, function () use ($injector, $callable) {
            return $injector->execute($callable);
        }, $rawParams);
        $twig->addFunction($function);
    }

    return $twig;
}
