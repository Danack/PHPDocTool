<?php

declare(strict_types=1);

namespace PHPDocTool\Cli;

use PHPDocTool\Repo\DocsToBuild\DocsToBuild;
use PHPDocTool\Repo\FileLastModifiedBuildTime\FileLastModifiedBuildTime;

use PHPDocTool\Repo\StatusStorage\StatusStorage;

class WatchSrc
{
    /** @var DocsToBuild */
    private $docsToBuild;

    /** @var StatusStorage */
    private $statusStorage;


    /** @var FileLastModifiedBuildTime */
    private $fileLastModifiedBuildTime;

    /**
     *
     * @param DocsToBuild $docsToBuild
     */
    public function __construct(
        DocsToBuild $docsToBuild,
        FileLastModifiedBuildTime $fileLastModifiedBuildTime,
        StatusStorage $statusStorage
    ) {
        $this->docsToBuild = $docsToBuild;
        $this->fileLastModifiedBuildTime = $fileLastModifiedBuildTime;
        $this->statusStorage = $statusStorage;
    }

    public function watchForModified()
    {
        continuallyExecuteCallable(
            \Closure::fromCallable([$this, 'internalWatchForModified']),
            10,
            0,
            1000
        );
    }

    private function testMtimeFile(string $filename)
    {
        $fullFilename = '/var/app/docs/en/' . $filename;
        $lastMTime = $this->fileLastModifiedBuildTime->getFileMtime($fullFilename);
        $fileMtime = filemtime($fullFilename);

        if ($lastMTime === null) {
            // ugh - this is the wrong place to do this, but it works
            $this->fileLastModifiedBuildTime->setFileMtime($fullFilename, $fileMtime);
            return true;
        }

        if ($fileMtime > $lastMTime) {
            // ugh - this is the wrong place to do this, but it works
            $this->fileLastModifiedBuildTime->setFileMtime($fullFilename, $fileMtime);
            return true;
        }

        else {
            echo "Skipping $filename as mtime !($fileMtime > $lastMTime) \n ";
        }

        return false;
    }


    private function internalWatchForModified()
    {
        $command = 'git ls-files -m';

        set_time_limit(60);

        $status = $this->statusStorage->getStatus();

        if ($status !== StatusStorage::STATUS_READY) {
            echo "App is not in ready state - skipping [$status].\n";
            return;
        }


        echo "About to use git to find the modified files\n";
        chdir('/var/app/docs/en');
        exec($command, $outputLines, $return_value);

        if ($return_value !== 0) {
            throw new \Exception("Something went wrong");
        }

        foreach ($outputLines as $outputLine) {
            $trimmedLine = trim($outputLine);

            if ($this->testMtimeFile($trimmedLine) !== false) {
                echo "Adding file to build: $trimmedLine\n";
                $this->docsToBuild->addDocToBuild($trimmedLine);
            }

        }
    }
}
