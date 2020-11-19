<?php

declare(strict_types = 1);

namespace PHPDocTool\Repo\FileBuildStatus;

interface FileBuildStatus
{
    public function setFileBuildFailed(
        string $filename,
        int $lastModifiedTime
    );

    public function setFileBuilt(
        string $filename,
        int $lastModifiedTime
    );

    public function doesFileNeedRebuilding(
        string $filename,
        int $currentLastModifiedTime
    );
}