<?php

declare(strict_types=1);

namespace PHPDocTool\Cli;

use PHPDocTool\Repo\StatusStorage\StatusStorage;
use PHPDocTool\Repo\DocsToBuild\DocsToBuild;


class GenerateDoc
{
    /** @var StatusStorage */
    private $statusStorage;

    /** @var DocsToBuild */
    private $docsToBuild;

    /**
     *
     * @param StatusStorage $statusStorage
     */
    public function __construct(
        StatusStorage $statusStorage,
        DocsToBuild $docsToBuild
    ) {
        $this->statusStorage = $statusStorage;
        $this->docsToBuild = $docsToBuild;
    }

    public function generateSingleDoc()
    {
        continuallyExecuteCallable(
            \Closure::fromCallable([$this, 'internalGenerateSingleDoc']),
            5,
            0,
            1000
        );
    }

    private function internalGenerateSingleDoc()
    {
        set_time_limit(60);

        $status = $this->statusStorage->getStatus();

        if ($status !== StatusStorage::STATUS_READY) {
            echo "App is not in ready state - skipping [$status].\n";
            return;
        }

        echo "let's do stuff.\n";

        $docToBuild = $this->docsToBuild->waitForDocToBuild();

        if ($docToBuild === null) {
            echo "Nothing to build yet.\n";
            return;
        }

        $filename = $docToBuild;

        $contents = file_get_contents('/var/app/docs/en/' . $filename);

        if ($contents === false) {
            echo "failed to read file $filename\n";
            return;
        }

        $xml_id = docs_get_xml_id($contents);

        if ($xml_id === null) {
            echo "Failed to find xml_id for file $filename \n";
            return;
        }

//        http://php.net/manual/en/function.proc-open.php
        $srcCommand = <<< TEXT
php ../doc-base/configure.php \
    --generate='/var/app/docs/en/$filename' \
    --with-partial=$xml_id
TEXT;


        echo "About to run configure to src -> xml\n";
        chdir('/var/app/docs/en');
        exec($srcCommand, $output, $return_value);

        echo "return value is [$return_value]\n";
        echo "output is:\n";
        var_dump($output);

        $filename = '.manual.' . $xml_id . '.xml';

        $tmpDirNameInOutputTemp = hash('sha256', (string)time());

        $tmpDirName = '/var/app/output_temp/' . $tmpDirNameInOutputTemp;

        $created = @mkdir($tmpDirName, 0755, true);

        $phdCommand = <<< TEXT
php /var/app/phd/render.php \
    --docbook /var/app/docs/doc-base/$filename \
    --memoryindex \
    --package PHP \
    --format php \
    --output ./output_temp/$tmpDirNameInOutputTemp
TEXT;

        echo "about to render xml -> docbook\n";
        chdir('/var/app');
        exec($phdCommand, $output, $return_value);

        echo "return value is [$return_value]\n";
        echo "output is:\n";
        var_dump($output);

        $source = $tmpDirName . '/php-web/' . $xml_id . '.php';
        $destination = '/var/app/output/php-web/' . $xml_id . '.php';

        $copied = @copy(
            $source,
            $destination
        );

        // @TODO - delete temporary directory here.
        if ($copied) {
            echo "File should be updated.\n";
        }
        else {
            echo "Failed to copy updated file to where it needs to be.\n";
            echo "source was: $source \n";
            echo "Destination was: $destination \n";
        }
    }
}
