<?php

use Danack\Console\Application;
use Danack\Console\Command\Command;
use Danack\Console\Input\InputArgument;

/**
 * @param Application $console
 */
function add_console_commands(Application $console)
{
    addDebugCommands($console);
    addProcessCommands($console);
    addStatusCommands($console);
}

/**
 * @param Application $console
 */
function addDebugCommands(Application $console)
{
    $command = new Command('debug:hello', 'PHPDocTool\Cli\Debug::hello');
    $command->setDescription("Test cli commands are working.");
    $console->add($command);

    $command = new Command('debug:debug', 'PHPDocTool\Cli\Debug::debug');
    $command->setDescription("Debug whatever code is in the debug function....");
    $console->add($command);

    $command = new Command('debug:listModifiedFiles', 'PHPDocTool\Cli\Debug::listModifiedFiles');
    $command->setDescription("List modified files");
    $console->add($command);

    $command = new Command('debug:build_file', 'PHPDocTool\Cli\Debug::addFileToBuild');
    $command->setDescription("add a file to be build, as if it was just modified.");
    $console->add($command);
}



/**
 * @param Application $console
 */
function addProcessCommands(Application $console)
{
    $command = new Command('process:generate_changed_doc', 'PHPDocTool\Cli\GenerateDoc::generateSingleDoc');
    $command->setDescription("Listens to the single doc changed queue and regenerates that doc.");
    $console->add($command);

    $command = new Command('process_clear:generate_changed_doc', 'PHPDocTool\Cli\Debug::clearDocsToBuild');
    $command->setDescription("clear the doc changed queue.");
    $console->add($command);

    $command = new Command('process:scan_for_modified', 'PHPDocTool\Cli\WatchSrc::watchForModified');
    $command->setDescription("Scan for modified files.");
    $console->add($command);
}




/**
 * @param Application $console
 */
function addStatusCommands(Application $console)
{
    $command = new Command('status:set', 'PHPDocTool\Cli\Status::set');
    $command->setDescription("Sets the overall status of the build system.");
    $command->addArgument('status', InputArgument::REQUIRED, 'The status of the system to set.');

    $console->add($command);
}
