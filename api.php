<?php

require "vendor/autoload.php";

// Note --> Secret key must be stored at server side. Is not a good idea has to fixed on the code
// There are 2 places here where it is called
$easyJwt = new \ByJG\Util\JwtWrapper('api.test.com', '5pbZNksFl4yhr6qUNnv/FyfPP3vbYkO8arGtuEX+EIU=');

try {
    $tokenDecomposed = $easyJwt->extractData();
} catch (\Exception $ex) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die($ex->getMessage());
}

echo "{ 'data': 'value' }";
