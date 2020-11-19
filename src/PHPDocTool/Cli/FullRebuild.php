<?php

declare(strict_types = 1);

namespace PHPDocTool\Cli;

use PHPDocTool\Repo\FullRebuildStatus\FullRebuildStatus;

class FullRebuild
{
    /** @var FullRebuildStatus */
    private $fullRebuildStatus;

    /**
     *
     * @param FullRebuildStatus $fullRebuildStatus
     */
    public function __construct(FullRebuildStatus $fullRebuildStatus)
    {
        $this->fullRebuildStatus = $fullRebuildStatus;
    }


    public function run()
    {
        continuallyExecuteCallable(
            [$this, 'runInternal'],
            1,
            1,
            60 * 10
        );
    }

    public function runInternal()
    {
        $fullRebuildRequired = $this->fullRebuildStatus->getFullRebuildRequired();

        if ($fullRebuildRequired !== true) {
            echo "Not time for full rebuild yet.\n";
            return;
        }

        echo "Time to do full rebuild.\n";

        $this->fullRebuildStatus->clearFullRebuildRequired();

        DOC_DIRECTORY="/var/app/docs/doc-base"


        # echo "Configuring docs - this takes about 2 minutes"
        $cmd = 'php "/var/app/docs/doc-base/configure.php"';

        $descriptorspec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $pipes = [];

        proc_open (
            $cmd,
            $descriptorspec,
            $pipes,
    //      $cwd = null,
    //      array $env = null,
    //      array $other_options = null
        );



# echo "Intial render of docs - this takes about 1.5 minutes"
# php "${PHD_DIRECTORY}/render.php" --docbook "${DOC_BASE_DIRECTORY}/.manual.xml" --memoryindex --package PHP --format php







    }
}
