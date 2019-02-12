<?php

declare(strict_types=1);

namespace PHPDocTool\Cli;

use PHPDocTool\Repo\StatusStorage\StatusStorage;

class Status
{
    public function set(string $status, StatusStorage $statusStorage)
    {
        $statusStorage->setStatus($status);
    }
}
