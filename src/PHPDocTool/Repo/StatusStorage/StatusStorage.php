<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\StatusStorage;


interface StatusStorage
{
    const STATUS_UNKNOWN = 'unknown';

    const STATUS_READY = 'ready';


    public function setStatus(string $status);

    public function getStatus(): string;
}
