<?php

declare(strict_types=1);

namespace PHPDocTool;

class FileBuildStatus
{
    /** @var BuildStatusOfFile[] */
    private $lastBuildForFiles = [];

    public function setFileBuildFailed(string $filename, int $lastModifiedTime)
    {
        $this->lastBuildForFiles[$filename] = new BuildStatusOfFile(false, $lastModifiedTime);
    }

    public function setFileBuilt(string $filename, int $lastModifiedTime)
    {
        $this->lastBuildForFiles[$filename]  = new BuildStatusOfFile(true, $lastModifiedTime);
    }

    public function doesFileNeedRebuilding(string $filename, int $currentLastModifiedTime)
    {
        // File hasn't been built yet, so needs building
        if (array_key_exists($filename, $this->lastBuildForFiles) === false) {
            return true;
        }

        $lastBuild = $this->lastBuildForFiles[$filename];

        // File has been modified since last build.
        if ($currentLastModifiedTime > $lastBuild->getLastMtime()) {
            return true;
        }

        return false;
    }


    public function showStatus()
    {
        printf("Status\n");
        foreach ($this->lastBuildForFiles as $filename => $lastBuildForFile) {
            $status = 'failed';

            if ($lastBuildForFile->isBuildSuccessfully()) {
                $status = 'built ';
            }

            printf(
                "%s : %s\n",
                $status,
                $filename
            );
        }
    }
}
