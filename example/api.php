<?php

require "vendor/autoload.php";

require_once "include.php";

$easyJwt = MyWrapper::getWrapper();

try {
    $tokenDecomposed = $easyJwt->extractData();
} catch (Exception $ex) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die($ex->getMessage());
}

echo "{ 'data': 'value' }";
