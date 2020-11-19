<?php

declare(strict_types = 1);

namespace PHPDocTool\Repo\FullRebuildStatus;

interface FullRebuildStatus
{
    public function setFullRebuildRequired();

    public function getFullRebuildRequired(): bool;
}
