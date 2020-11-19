<?php

$config = [];

$config['phpdoctool']['redis'] = [
    // 'host'      => 'redis',
    'host'      => '10.254.254.254',
    'password'  => 'UuzaXA6cZgkX83GPNxw2ByUUQPR2sF6H',
    'port'      => 6379
];

$config['phpdoctool']['twig'] = [
    'cache' => false,
    'debug' => true,
];


return $config;

