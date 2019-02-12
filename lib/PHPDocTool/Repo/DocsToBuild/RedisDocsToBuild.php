<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\DocsToBuild;

use PHPDocTool\Key\AppKeys;

class RedisDocsToBuild implements DocsToBuild
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

    public function addDocToBuild(string $docToBuild)
    {
        $this->redis->rPush(AppKeys::DOCUMENTS_TO_BUILD, $docToBuild);
    }

    public function waitForDocToBuild(): ?string
    {
        // A nil multi-bulk when no element could be popped and the timeout expired.
        // A two-element multi-bulk with the first element being the name of the key
        // where an element was popped and the second element being the value of
        // the popped element.
        $redisData = $this->redis->blpop([AppKeys::DOCUMENTS_TO_BUILD], 5);

        if (count($redisData) === 0) {
            return null;
        }

        [$keyname, $data] = $redisData;

        return $data;
    }

    public function clearForDocsToBuild()
    {
        $this->redis->del(AppKeys::DOCUMENTS_TO_BUILD);
    }
}
