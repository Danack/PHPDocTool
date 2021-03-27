<?php

declare(strict_types = 1);


class PHPDocToolConfig
{
    public const BASE_WORKING_DIRECTORY = __DIR__ . '/docs';

    # The language directory
    public const DOC_EN_DIRECTORY = self::BASE_WORKING_DIRECTORY . "/en";

    # The doc 'base' directory
    public const DOC_BASE_DIRECTORY= self::BASE_WORKING_DIRECTORY .  "/doc-base";

    # PHD directory
    public const PHD_DIRECTORY = self::BASE_WORKING_DIRECTORY . "/phd";

    # Website directory
    public const PHP_NET_DIRECTORY = self::BASE_WORKING_DIRECTORY . "/php.net";
}
