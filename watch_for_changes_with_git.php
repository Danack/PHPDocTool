<?php

declare(strict_types=1);

error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);

require_once __DIR__ . "/src/functions.php";
require_once __DIR__ . "/src/BuildStatusOfFile.php";
require_once __DIR__ . "/src/FileBuildStatus.php";
require_once __DIR__ . "/src/GitWatchSrc.php";


$gitWatchSrc = new \PHPDocTool\GitWatchSrc();

continuallyExecuteCallable(
    \Closure::fromCallable([$gitWatchSrc, 'internalWatchForModifiedWithGit']),
    5,
    0,
    3600 * 12 // run for half a day max.
);

echo "Finished watching";