<?php

function check_git_available()
{
     $last_line = exec("git --version", $output, $return_var);

     if ($return_var !== 0) {
         echo "Failed to execute git to check version.";
         exit(-1);
     }

//     var_dump($last_line);
    //git version 2.14.1"

    $matched = preg_match("#git version ([\d\.]*)#iu", $last_line, $match);//, PREG_OFFSET_CAPTURE);

    if (!$matched) {
        echo "Failed to read version of git. If stuff breaks, that's the problem\n";
        return;
    }

    $version = $match[1];
    // TODO - check the string like "2.14.1" is okay. Can
    // wait until we have a minimum version...
}

/**
 * Get encoding of a file, regarding his XML's header.
 *
 * @param string $content The content of the file
 * @return string  The charset as a string.
 */
function getEncoding(string $content)
{
    $content = preg_replace('/\\s+/', ' ', $content);

    $match = array();
    preg_match('!<\?xml(.+)\?>!U', $content, $match);
    $xmlinfo = $this->parseAttribute($match); //nice code dan

    $charset = isset($xmlinfo[1]['encoding'])
        ? strtolower($xmlinfo[1]['encoding'])
        : 'iso-8859-1';

    return $charset;
}


function docs_get_xml_id($content)
{
    // All xmlid
    $match = array();
    if (preg_match_all('/xml:id=("|\')(.*?)("|\')/', $content, $match)) {
        return implode('|',$match[2]);
    }

    return null;
}

/**
 * Get the information from the content of a file.
 *
 * @param string $content The content of the file.
 * @return array - An associated array of informations.
 */
function getInfo(string $content)
{
    $info = array(
        'rev'        => 0,
        'en-rev'     => 0,
        'maintainer' => 'NULL',
        'reviewed'   => 'NULL',
        'reviewed_maintainer' => 'NULL',
        'status'     => '-',
        'xmlid'      => 'NULL',
        'content'    => $content
    );

    // revision tag
    $match = array();
    preg_match('/<!-- .Revision: (\d+) . -->/', $content, $match);
    if (!empty($match)) {
        $info['rev'] = $match[1];
    }

    // Rev tag
    $match = array();
    preg_match('/<!--\s*EN-Revision:\s*((\d+)|(n\/a))\s*Maintainer:\s*(\\S*)\s*Status:\s*(.+)\s*-->/U', $content, $match);
    if (!empty($match)) {
        $info['en-rev']     = ($match[1] == 'n/a') ? 0 : $match[1];
        $info['maintainer'] = $match[4];
        $info['status']     = $match[5];
    }

    // Reviewed tag
    $match = array();
    if (preg_match('/<!--\s*Reviewed:\s*(.*?)\s*(Maintainer:\s*(\\S*)\s*)?-->/i', $content, $match)) {
        $info['reviewed'] = ( isset($match[1]) ) ? trim($match[1]) : NULL;
        $info['reviewed_maintainer'] = ( isset($match[3]) ) ? trim($match[3]) : NULL;
    }

    // All xmlid
    $match = array();
    if (preg_match_all('/xml:id=("|\')(.*?)("|\')/', $content, $match)) {
        $info['xmlid'] = implode('|',$match[2]);
    }

    return $info;
}





/**
 * Self-contained monitoring system for system signals
 * returns true if a 'graceful exit' like signal is received.
 *
 * We don't listen for SIGKILL as that needs to be an immediate exit,
 * which PHP already provides.
 * @return bool
 */
function checkSignalsForExit()
{
    static $initialised = false;
    static $needToExit = false;

    $fnSignalHandler = function ($signalNumber) use (&$needToExit) {
        $needToExit = true;
    };

    if ($initialised === false) {
        pcntl_signal(SIGINT, $fnSignalHandler, false);
        pcntl_signal(SIGQUIT, $fnSignalHandler, false);
        pcntl_signal(SIGTERM, $fnSignalHandler, false);
        pcntl_signal(SIGHUP, $fnSignalHandler, false);
        pcntl_signal(SIGUSR1, $fnSignalHandler, false);
        $initialised = true;
    }

    pcntl_signal_dispatch();

    return $needToExit;
}

/**
 * Repeatedly calls a callable until it's time to stop
 *
 * @param callable $callable - the thing to run
 * @param int $secondsBetweenRuns - the minimum time between runs
 * @param int $sleepTime - the time to sleep between runs
 * @param int $maxRunTime - the max time to run for, before returning
 */
function continuallyExecuteCallable($callable, int $secondsBetweenRuns, int $sleepTime, int $maxRunTime)
{
    $startTime = microtime(true);
    $lastRuntime = 0;
    $finished = false;

    echo "starting continuallyExecuteCallable \n";
    while ($finished === false) {
        $shouldRunThisLoop = false;
        if ($secondsBetweenRuns === 0) {
            $shouldRunThisLoop = true;
        }
        else if ((microtime(true) - $lastRuntime) > $secondsBetweenRuns) {
            $shouldRunThisLoop = true;
        }

        if ($shouldRunThisLoop === true) {
            $callable();
            $lastRuntime = microtime(true);
        }

        if (checkSignalsForExit()) {
            break;
        }

        if ($sleepTime > 0) {
            sleep($sleepTime);
        }

        if ((microtime(true) - $startTime) > $maxRunTime) {
            echo "Reach maxRunTime - finished = true\n";
            $finished = true;
        }
    }

    echo "Finishing continuallyExecuteCallable\n";
}