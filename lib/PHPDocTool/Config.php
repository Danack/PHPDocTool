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

//    const EXAMPLE_SMS_NOTIFICATION_ENABLED = ['osf', 'sms_notifications_enabled'];
//
//    const EXAMPLE_MANDRILL_INFO = ['osf', 'mandrill'];
//
//    const EXAMPLE_EXCEPTION_LOGGING = ['osf', 'exception_logging'];
//
//    const OSF_CORS_ALLOW_ORIGIN = ['osf', 'cors', 'allow_origin'];
//
//    const OSF_ENVIRONMENT = ['osf', 'env'];
//
//    const OSF_ALLOWED_ACCESS_CIDRS = ['osf', 'allowed_access_cidrs'];


    const TWIG_INFO_CACHE = ['twig', 'cache'];
    const TWIG_INFO_DEBUG = ['twig', 'debug'];

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
//
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
