<?php

declare(strict_types = 1);

namespace PHPDocTool\AppController;

use PHPDocTool\Repo\FullRebuildStatus\FullRebuildStatus;
use SlimAuryn\Response\JsonResponse;

class Controls
{
    public function triggerFullRebuild(FullRebuildStatus $fullRebuildStatus)
    {
        $fullRebuildStatus->setFullRebuildRequired();

        return new JsonResponse("Ok");
    }
}
