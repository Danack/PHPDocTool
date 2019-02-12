<?php

declare(strict_types=1);

namespace PHPDocTool\Cli;

use PHPDocTool\Service\ModifiedFilesFinder;
use PHPDocTool\Repo\DocsToBuild\DocsToBuild;
use SlimAuryn\Response\HtmlResponse;

class Debug
{
    public function hello()
    {
        return new HtmlResponse("Hello");
    }


    public function debug()
    {
        $contents = file_get_contents(ROOT_PATH . '/output/php-web/imagick.newpseudoimage.php');

        if ($contents === false) {
            throw new \Exception("Failed to read file.");
        }

        var_dump(getInfo($contents));
    }



    public function addFileToBuild(DocsToBuild $docsToBuild)
    {
        $filename = 'reference/imagick/imagick/newimage.xml';

        $docsToBuild->addDocToBuild($filename);
    }

    public function listModifiedFiles(ModifiedFilesFinder $modifiedFilesFinder)
    {

        $files = $modifiedFilesFinder->findFiles();

        if (count($files) === 0) {
            echo "There are no modified files";
            return;
        }

        echo "Modified files are:\n";
        foreach ($files as $file) {
            echo "  $file\n";
        }
    }


    public function clearDocsToBuild(DocsToBuild $docsToBuild)
    {
        $docsToBuild->clearForDocsToBuild();
    }
}


