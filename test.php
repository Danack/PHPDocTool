<?php

declare(strict_types=1);


echo "hello!\n";

# echo "Configuring docs - this takes about 2 minutes"
$cmd = 'php "/var/app/docs/doc-base/configure.php"';

$descriptorspec = [
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$pipes = [];

$proc_handle = proc_open (
    $cmd,
    $descriptorspec,
    $pipes
//      $cwd = null,
//      array $env = null,
//      array $other_options = null
);


while(true) {
    echo "wat\n";
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    var_dump($stdout, $stderr);

    sleep(1);

//    echo "Proc status is: " . proc_get_status($proc_handle) . "\n";

}

echo "fin.\n";