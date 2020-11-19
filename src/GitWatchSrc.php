<?php

declare(strict_types=1);

namespace PHPDocTool;


class GitWatchSrc
{
    private $fileLastBuildTime = [];

    public function __construct()
    {
        $this->fileBuildStatus = new FileBuildStatus();
    }


//    private function isFileModifiedSinceLastBuild(string $filename)
//    {
//        // We haven't built this file yet, so it needs to be built.
//        if (array_key_exists($fullFilename, $this->fileLastBuildTime) === false) {
//            $this->fileLastBuildTime[$fullFilename] = $fileMtime;
//            return true;
//        }
//
//        // File has been modified since last built, so needs rebuilding.
//        if ($fileMtime > $this->fileLastBuildTime[$fullFilename]) {
//            return true;
//        }
//
//        return false;
//    }



    public function internalWatchForModifiedWithGit()
    {
        set_time_limit(60);
//        echo "About to use git to find the modified files\n";
        chdir(ROOT_PATH . '/docs/en');

        $gitCommand = 'git ls-files -m';

        exec($gitCommand, $outputLines, $return_value);

        if ($return_value !== 0) {
            throw new \Exception("Something went wrong finding modified files. Please check you can run [$gitCommand] successfully.");
        }

        $anyFilesRendered = false;

        foreach ($outputLines as $outputLine) {
            $filename = trim($outputLine);
            if (doesFileNeedCompiling($filename) !== true) {
                continue;
            }
            $fullFilename = ROOT_PATH . '/docs/en/' . $filename;
            $fileMtime = filemtime($fullFilename);

            if ($this->fileBuildStatus->doesFileNeedRebuilding($filename, $fileMtime) === false) {
                continue;
            }

            $anyFilesRendered = true;

            [$xml_id, $error] = renderSingleFileForManual($filename);



            if ($error) {
                $this->fileBuildStatus->setFileBuildFailed($filename, $fileMtime);
                printf(
                    "Failed to render file %s: %s\n",
                    $filename,
                    $error
                );
                continue;
            }
            echo "yay, compiled file $filename for xml_id $xml_id\n";


            $this->fileBuildStatus->setFileBuilt($filename, $fileMtime);
        }

        if ($anyFilesRendered) {
            $this->fileBuildStatus->showStatus();
        }
    }
}
