<?php

declare(strict_types=1);

namespace PHPDocTool;

class BuildStatusOfFile
{
    /** @var  bool */
    private $buildSuccessfully;

    /** @var int */
    private $lastMtime;

    /**
     *
     * @param bool $buildSuccessfully
     * @param int $lastMtime
     */
    public function __construct(bool $buildSuccessfully, int $lastMtime)
    {
        $this->buildSuccessfully = $buildSuccessfully;
        $this->lastMtime = $lastMtime;
    }

    /**
     * @return bool
     */
    public function isBuildSuccessfully(): bool
    {
        return $this->buildSuccessfully;
    }

    /**
     * @return int
     */
    public function getLastMtime(): int
    {
        return $this->lastMtime;
    }


}
