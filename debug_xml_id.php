<?php

declare(strict_types=1);

function getXmlId($content)
{
    // All xmlid
    $match = array();
    if (preg_match_all('/xml:id=("|\')(.*?)("|\')/', $content, $match)) {
        return implode('|', $match[2]);
    }

    return null;
}

$path = '/var/app/doc-en/en/reference/imagick/imagick/newimage.xml';
$contents = file_get_contents($path);
$xmlId = getXmlId($contents);

echo "xmlId is " . var_export($xmlId, true) . "\n";



//python3.5 watchmedo.py
//
//
//https://github.com/gorakhargosh/watchdog
//
//
//watchmedo log \
//--patterns="*.py;*.txt" \
//--ignore-directories \
//--recursive \
//.