<?php

declare(strict_types=1);

namespace PHPDocTool\Service;

class ModifiedFilesFinder
{
    public function findFiles()
    {
        $command = 'git ls-files -m';

        $directory = __DIR__ . '/../../../docs/en';

        $oldDirectory = getcwd();

        chdir($directory);

        $outputLines = [];
        $return_var = 0;

        exec($command, $outputLines, $return_var);

        if ($return_var !== 0) {
            throw new \Exception("Failed to list modified files");
        }

        chdir($oldDirectory);
        $trimmedOutputLines = [];

        foreach ($outputLines as $outputLine) {
            $trimmedOutputLines[] = trim($outputLine);
        }

        return $trimmedOutputLines;
    }
}
