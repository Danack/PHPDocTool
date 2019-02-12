<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\FileLastModifiedBuildTime;


/**
 * We store the mtime of the file when we trigger a build of that file
 *
 * Subsequent builds of the file should only happen if the mtime is now later.
 *
 * @package PHPDocTool\Repo\DocsToBuild
 */
interface FileLastModifiedBuildTime
{
    public function setFileMtime(string $filename, int $mtime);

    public function getFileMtime(string $filename): ?int;
}


