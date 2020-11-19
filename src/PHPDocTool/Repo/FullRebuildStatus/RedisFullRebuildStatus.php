<?php

declare(strict_types = 1);

namespace PHPDocTool\Repo\FullRebuildStatus;

use PHPDocTool\Key\AppKeys;
use c\Repo\StatusStorage\StatusStorage;


class RedisFullRebuildStatus implements FullRebuildStatus
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

    public function clearFullRebuildRequired()
    {
        $this->redis->delete(AppKeys::FULL_REBUILD_REQUIRED);
    }

    public function setFullRebuildRequired()
    {
         $this->redis->set(
             AppKeys::FULL_REBUILD_REQUIRED,
             'true'
         );
    }

    public function getFullRebuildRequired(): bool
    {
        $result = $this->redis->get(AppKeys::FULL_REBUILD_REQUIRED);

        if ($result === false) {
            return false;
        }

        return ($result === 'true');
    }
}
