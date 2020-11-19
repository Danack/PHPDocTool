<?php

declare(strict_types=1);

namespace PHPDocTool;

use PHPDocTool\Config\RedisConfig;
use PHPDocTool\Config\TwigConfig;

class Config
{
    const ENVIRONMENT_LOCAL = 'local';
    const ENVIRONMENT_PROD = 'prod';

    const EXAMPLE_DATABASE_INFO = ['osf', 'database'];

    const REDIS_INFO = ['phpdoctool', 'redis'];

    const TWIG_INFO_CACHE = ['phpdoctool', 'twig', 'cache'];
    const TWIG_INFO_DEBUG = ['phpdoctool', 'twig', 'debug'];

    public static function get($index)
    {
        return getConfig($index);
    }

    public static function testValuesArePresent()
    {
        $rc = new \ReflectionClass(self::class);
        $constants = $rc->getConstants();

        foreach ($constants as $constant) {
            $value = getConfig($constant);
        }
    }

//    public function getCorsAllowOriginForApi()
//    {
//        return $this->get(self::OSF_CORS_ALLOW_ORIGIN);
//    }
//
//    public function getEnvironment()
//    {
//        return $this->get(self::OSF_ENVIRONMENT);
//    }
//
//    public function getAllowedAccessCidrs()
//    {
//        return $this->get(self::OSF_ALLOWED_ACCESS_CIDRS);
//    }

    public function getTwigConfig() : TwigConfig
    {
        return new TwigConfig(
            getConfig(self::TWIG_INFO_CACHE),
            getConfig(self::TWIG_INFO_DEBUG)
        );
    }

    public function getRedisConfig(): RedisConfig
    {
        $redisInfo = getConfig(self::REDIS_INFO);

        return new RedisConfig(
            $redisInfo['host'],
            $redisInfo['password'],
            $redisInfo['port']
        );
    }
}
