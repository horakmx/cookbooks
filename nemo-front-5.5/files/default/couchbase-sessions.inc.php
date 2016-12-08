<?php

$aConfig = array();

$aConfig['servers'][] = array(
    'addr' => '127.0.0.1',
    'port' => 8091,
);


$aConfig['bucket'] = 'dev-front-sessions';


$aConfig['serialization'] = COUCHBASE_SERTYPE_IGBINARY;

return $aConfig;

