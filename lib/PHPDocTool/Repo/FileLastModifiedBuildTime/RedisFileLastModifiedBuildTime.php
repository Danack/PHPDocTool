<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\FileLastModifiedBuildTime;

use PHPDocTool\Key\FileLastModifiedTimeKey;

class RedisFileLastModifiedBuildTime implements FileLastModifiedBuildTime
{
    /** @var \Redis */
    private $redis;

    /**
     *
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function setFileMtime(string $filename, int $mtime)
    {
        $key = FileLastModifiedTimeKey::getAbsoluteKeyName($filename);

        $this->redis->set($key, $mtime);
    }

    public function getFileMtime(string $filename): ?int
    {
        $key = FileLastModifiedTimeKey::getAbsoluteKeyName($filename);

        $result = $this->redis->get($key);

        if ($result === false) {
            return null;
        }

        return intval($result);
    }
}
