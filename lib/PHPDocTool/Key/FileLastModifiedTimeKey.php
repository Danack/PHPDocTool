<?php

declare(strict_types=1);

namespace PHPDocTool\Key;

class FileLastModifiedTimeKey
{
    public static function getAbsoluteKeyName(string $filename) : string
    {
        return str_replace('\\', '', __CLASS__) . '_' . $filename;
    }

//    public static function getWildcardKeyName() : string
//    {
//        return str_replace('\\', '', __CLASS__) . '_*';
//    }
}


