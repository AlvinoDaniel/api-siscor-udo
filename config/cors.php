<?php

return [

/*
|--------------------------------------------------------------------------
| Laravel CORS
|--------------------------------------------------------------------------
|
| allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
| to accept any value.
|
*/
'paths' => ['api/*', 'sanctum/csrf-cookie', 'api'],
'supports_credentials' => true,
'allowedOrigins' => ['*'],// ex: ['abc.com', 'api.abc.com']
'allowedHeaders' => ['*'],
'allowedMethods' => ['*'],// ex: ['GET', 'POST', 'PUT', 'DELETE']
'exposedHeaders' => [],
'maxAge' => 0,

];