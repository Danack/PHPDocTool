<?php

$options = [];

$dockerHost  = '10.254.254.254';

//$options['osf']['database'] = [
//    'schema' => 'osf',
//    'host' => $dockerHost,
//    'username' => 'osf',
//    'password' => 'D9cACV8Pue3CvM93',
//];

$options['phpdoctool']['redis'] = [
    'host' => 'redis',
    'password' => 'UuzaXA6cZgkX83GPNxw2ByUUQPR2sF6H',
    'port' => 6379
];


//$options['osf']['cors'] = [
//    'allow_origin' => 'http://local.app.opensourcefees.com'
//];

//$options['osf']['twilio'] = [
//    'sid'   => 'AC74c949405c83c2aaf945d57070d88c4c',
//    'token' => '26b9ae3e64cbce890555a4ea84407d47',
//    'oa'    => '+447479271031',
//];
//


// production - in production
// production in staging
// 'develop' in develop
// local in local

//$options['osf']['env'] = 'local';
//
//$options['osf']['allowed_access_cidrs'] = [
//    '86.7.192.0/24',
//    '10.0.0.0/8',
//    '127.0.0.1/24',
//    "172.0.0.0/8"   // docker local networking
//];


$options['twig'] = [
    'cache' => false,
    'debug' => true
];
