<?php

declare(strict_types = 1);

require __DIR__ . "/functions_otw.php";
require __DIR__ . "/src/PHPDocToolConfig.php";

check_git_available();

$mode = 'setup_directories';

$config = new PHPDocToolConfig();

if ($mode === 'setup_directories') {

    // Check working directory exists($config);
    // check out the $DOC_EN_DIRECTORY if it doesn't already exist
    // check out the $DOC_base_DIRECTORY if it doesn't already exist
    // check out the $PHD_DIRECTORY if it doesn't already exist
    // check out the $PHP_NET_DIRECTORY if it doesn't already exist
}