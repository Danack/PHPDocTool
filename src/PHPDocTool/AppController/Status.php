<?php

declare(strict_types=1);

namespace PHPDocTool\AppController;

use SlimAuryn\Response\TwigResponse;
use PHPDocTool\Repo\StatusStorage\StatusStorage;
use SlimAuryn\Response\JsonResponse;

class Status
{
    public function get()
    {
        return new TwigResponse('pages/status.html');
    }

    public function getJson(StatusStorage $statusStorage)
    {
        $params = [
            'html_system_status' => $statusStorage->getStatus(),
        ];

        return new JsonResponse($params);
    }
}
