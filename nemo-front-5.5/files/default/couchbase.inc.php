<?php

$aConfig = array();

$aConfig['servers'][] = array(
    'addr' => '127.0.0.1',
    'port' => 8091,
);


#$aConfig['bucket'] = 'load-test';

$aConfig['bucket'] = 'dev-front';



$aConfig['serialization'] = COUCHBASE_SERTYPE_IGBINARY;

return $aConfig;

