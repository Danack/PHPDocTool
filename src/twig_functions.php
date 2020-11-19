<?php

declare(strict_types=1);

function memory_debug()
{
    $memoryUsed = memory_get_usage(true);
    return "<!-- " . number_format($memoryUsed) . " -->";
}

function request_nonce(\Osf\Service\RequestNonce $requestNonce)
{
    return $requestNonce->getRandom();
}
