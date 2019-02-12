<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\StatusStorage;

use PHPDocTool\Key\AppKeys;

class RedisStatusStorage implements StatusStorage
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

    public function setStatus(string $status)
    {
        $this->redis->set(
            AppKeys::SYSTEM_STATUS,
            $status
        );
    }

    public function getStatus(): string
    {
        $result = $this->redis->get(AppKeys::SYSTEM_STATUS);

        if ($result === false) {
            return StatusStorage::STATUS_UNKNOWN;
        }

        return $result;
    }
}
