<?php


// Each row of this array should return an array of:
// - The path to match
// - The method to match
// - The route info
// - (optional) A setup callable to add middleware/DI info specific to that route
//
// This allows use to configure data per endpoint e.g. the endpoints that should be secured by
// an api key, should call an appropriate callable.
return [

    ['/build_container', 'GET', 'PHPDocTool\AppController\Index::getFrame'],

    ['/build_status', 'GET', 'PHPDocTool\AppController\Status::getJson'],

    [
        '/doctool/trigger_build',
        'GET',
        'PHPDocTool\AppController\Controls::triggerFullRebuild'
    ],

    [
        '/doctool/trigger_build',
        'POST',
        'PHPDocTool\AppController\Controls::triggerFullRebuild'
    ],

    ['/trigger_build', 'POST', 'PHPDocTool\AppController\Controls::triggerFullRebuild'],


    ['/', 'GET', 'PHPDocTool\AppController\Index::getFrame'],
    ['/{any:.*}', 'GET', 'PHPDocTool\AppController\Index::getFrame'],
];
