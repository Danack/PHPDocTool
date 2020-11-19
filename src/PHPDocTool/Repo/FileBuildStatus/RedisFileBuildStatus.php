<?php

declare(strict_types = 1);

namespace PHPDocTool\Repo\FileBuildStatus;

class RedisFileBuildStatus implements FileBuildStatus
{
    public function setFileBuildFailed(string $filename, int $lastModifiedTime)
    {
        throw new \Exception("setFileBuildFailed not implemented yet.");
    }

    public function setFileBuilt(string $filename, int $lastModifiedTime)
    {
        throw new \Exception("setFileBuilt not implemented yet.");
    }

    public function doesFileNeedRebuilding(string $filename, int $currentLastModifiedTime)
    {
        throw new \Exception("doesFileNeedRebuilding not implemented yet.");
    }
}
