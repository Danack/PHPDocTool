<?php

declare(strict_types=1);

namespace PHPDocTool\Repo\DocsToBuild;


interface DocsToBuild
{
    public function addDocToBuild(string $docToBuild);

    public function waitForDocToBuild(): ?string;

    public function clearForDocsToBuild();
}
