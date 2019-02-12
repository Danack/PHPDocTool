<?php

declare(strict_types=1);

namespace PHPDocTool\Component;

use PHPDocTool\Repo\StatusStorage\StatusStorage;


class SystemStatus
{
    /** @var StatusStorage */
    private $statusStorage;

    /**
     *
     * @param StatusStorage $statusStorage
     */
    public function __construct(StatusStorage $statusStorage)
    {
        $this->statusStorage = $statusStorage;
    }


    public function render()
    {
        $params = [
            ':html_system_status' => $this->statusStorage->getStatus(),
        ];

        $html = <<< HTML
<div class="system_status">
  :html_system_status
</div>

HTML;

        return esprintf($html, $params);
    }
}
