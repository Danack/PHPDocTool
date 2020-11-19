<?php

declare(strict_types=1);

/**
 * Repeatedly calls a callable until it's time to stop
 *
 * @param callable $callable - the thing to run
 * @param int $secondsBetweenRuns - the minimum time between runs
 * @param int $sleepTime - the time to sleep between runs
 * @param int $maxRunTime - the max time to run for, before returning
 */
function continuallyExecuteCallable(
    $callable,
    int $secondsBetweenRuns,
    int $sleepTime,
    int $maxRunTime
) {
    $startTime = microtime(true);
    $lastRuntime = 0;
    $finished = false;

    echo "starting continuallyExecuteCallable \n";
    while ($finished === false) {
        set_time_limit(120);

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
 * @param $content
 * @return string|null
 */
function docs_get_xml_id($content)
{
    // All xmlid
    $match = array();
    if (preg_match_all('/xml:id=("|\')(.*?)("|\')/', $content, $match)) {

        // we can only cope with one id
        foreach ($match[2] as $id){
            return $id;
        }

        return implode('|', $match[2]);
    }

    return null;
}


/**
 * @param $filename
 * @return array
 */
function renderSingleFileForManual($filename)
{
    $fullpathOfSourceFile = ROOT_PATH . '/docs/en/' . $filename;

    echo "renderSingleFileForManual " . $fullpathOfSourceFile . "\n";

    $contents = file_get_contents($fullpathOfSourceFile);

    if ($contents === false) {
        return [null, "failed to read file $filename"];
    }

    $xml_id = docs_get_xml_id($contents);
    if ($xml_id === null) {
        chdir(ROOT_PATH);
        return [null, "Failed to find xml_id for file $filename."];
    }

    $srcCommand = <<< TEXT
php ../doc-base/configure.php \
    --generate='$fullpathOfSourceFile' \
    --with-partial="$xml_id"
TEXT;


    echo "About to run configure to src -> xml\n";
    chdir(ROOT_PATH . '/docs/en');
    exec($srcCommand, $output, $return_value);

    if ($return_value !== 0) {
        chdir(ROOT_PATH);
        return [null, "Failed to run [$srcCommand]"];
    }

    $filename = '.manual.' . $xml_id . '.xml';
    $tmpDirNameInOutputTemp = hash('sha256', (string)time());

    $tmpDirName = ROOT_PATH . '/output_temp/' . $tmpDirNameInOutputTemp;

    $created = @mkdir($tmpDirName, 0755, true);

    $fullpath = ROOT_PATH . "/docs/doc-base/" . $filename;
    $phdPath = ROOT_PATH . '/phd/render.php';

    chdir(ROOT_PATH);
    $phdCommand = <<< TEXT
php $phdPath \
    --docbook $fullpath \
    --memoryindex \
    --package PHP \
    --format php \
    --output ./output_temp/$tmpDirNameInOutputTemp
TEXT;

    echo "about to render xml -> docbook\n";


    chdir(ROOT_PATH);
    exec($phdCommand, $output, $return_value);

//        echo "return value is [$return_value]\n";
//        echo "output is:\n";
//        var_dump($output);

    $source = $tmpDirName . '/php-web/' . $xml_id . '.php';
    $destination = ROOT_PATH . '/output/php-web/' . $xml_id . '.php';

    $copied = @copy(
        $source,
        $destination
    );

    // @TODO - delete temporary directory here.
    if ($copied) {
        //        echo "File should be updated.\n";
        return [$xml_id, null];
    }

    return [null, "Failed to copy updated file to where it needs to be.\n"];
//        echo "source was: $source \n";
//        echo "Destination was: $destination \n";
}



function doesFileNeedCompiling($filename)
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (strcasecmp($ext, 'xml') === 0) {
//        echo "File $filename is an xml file - interesting!\n";
        return true;
    }
//    echo "File $filename is not xml file - BORING!\n";

    return false;
}


function saneErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine)
{
    if (error_reporting() === 0) {
        // Error reporting has been silenced
        if ($errorNumber !== E_USER_DEPRECATED) {
            // Check it isn't this value, as this is used by twig, with error suppression. :-/
            return true;
        }
    }
    if ($errorNumber === E_DEPRECATED) {
        return false;
    }
    if ($errorNumber === E_CORE_ERROR || $errorNumber === E_ERROR) {
        // For these two types, PHP is shutting down anyway. Return false
        // to allow shutdown to continue
        return false;
    }
    $message = "Error: [$errorNumber] $errorMessage in file $errorFile on line $errorLine.";
    throw new \Exception($message);
}



function showException(\Exception $exception)
{
    echo "oops";
    do {
        echo get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        echo nl2br($exception->getTraceAsString());

        echo "<br/><br/>";
        $exception = $exception->getPrevious();
    } while ($exception !== null);
}



function appErrorHandler($request, $response, $exception)
{
    /** @var \Throwable $exception */
    $text = "";
    do {
        $text .= "Exception type: " . get_class($exception) . "<br/>";

        $text .= $exception->getMessage() . "<br/><br/>\n\n";

//        $text .= str_replace("#", "<br/>#", nl2br($exception->getTraceAsString())). "<br/><br/>\n\n";
        $text .= str_replace(
                "#",
                "<br/>#",
                nl2br(getExceptionStack($exception))
            ). "<br/><br/>\n\n";
    } while (($exception = $exception->getPrevious()) !== null);

    error_log($text);

    return $response->withStatus(500)
        ->withHeader('Content-Type', 'text/html')
        ->write($text);
}



function getExceptionStack(\Throwable $exception)
{
    var_dump(get_class($exception), $exception->getLine(),
        $exception->getFile(), $exception->getMessage());

    exit(0);
}

/**
 * @param array $indexes
 * @return mixed
 * @throws Exception
 */
function getConfig(array $indexes)
{
    static $config = null;

    if ($config === null) {
        require __DIR__ . '/../config.php';
    }

    $data = $config;

    foreach ($indexes as $index) {
        if (array_key_exists($index, $data) === false) {
            throw new \Exception("Config doesn't contain an element for $index, for indexes [" . implode('|', $indexes) . "]");
        }

        $data = $data[$index];
    }

    return $data;
}
