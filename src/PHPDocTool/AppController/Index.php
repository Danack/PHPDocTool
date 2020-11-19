<?php

declare(strict_types=1);

namespace PHPDocTool\AppController;

use SlimAuryn\Response\TwigResponse;

class Index
{
    public function debug()
    {
        foreach ($_SERVER as $k => $v) {
            echo "$k => $v <br/>";
        }

        echo "Hello world";
        exit(0);
    }


    public function getFrame()
    {
        return new TwigResponse('iframe/container.html');
    }
}
