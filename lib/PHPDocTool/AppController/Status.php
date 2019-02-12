<?php

declare(strict_types=1);

namespace PHPDocTool\AppController;

use SlimAuryn\Response\TwigResponse;

class Status
{
    public function get()
    {
        return new TwigResponse('pages/status.html');
    }
}
