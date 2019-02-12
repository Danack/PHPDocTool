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
    ['/status', 'GET', 'PHPDocTool\AppController\Status::get'],







    ['/{any:.*}', 'GET', 'PHPDocTool\AppController\Index::get'],
];
