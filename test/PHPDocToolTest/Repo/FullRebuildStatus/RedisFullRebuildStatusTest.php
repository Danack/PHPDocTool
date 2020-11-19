<?php

declare(strict_types = 1);

use PHPDocToolTest\BaseTestCase;
use PHPDocTool\Repo\FullRebuildStatus\RedisFullRebuildStatus;

use PHPDocToolTest\Auryn;

/**
 * @group wip
 */
class RedisFullRebuildStatusTest extends BaseTestCase
{
    use Auryn;

    public function testEmptyDefaultsToFalse()
    {
        $redisFullRebuildStatus = $this->make(RedisFullRebuildStatus::class);
        $redisFullRebuildStatus->clearFullRebuildRequired();
        $rebuildRequired = $redisFullRebuildStatus->getFullRebuildRequired();
        $this->assertFalse($rebuildRequired);
    }

    public function testWorks()
    {
        $redisFullRebuildStatus = $this->make(RedisFullRebuildStatus::class);
        $redisFullRebuildStatus->clearFullRebuildRequired();
        $rebuildRequired = $redisFullRebuildStatus->getFullRebuildRequired();
        $this->assertFalse($rebuildRequired);

        $redisFullRebuildStatus->setFullRebuildRequired();
        $rebuildRequired = $redisFullRebuildStatus->getFullRebuildRequired();
        $this->assertTrue($rebuildRequired);
    }
}
