<?php
namespace Example;

use Example\Classes\MyWrapper;

require "vendor/autoload.php";

$easyJwt = MyWrapper::getWrapper();

$jwt = $easyJwt->createJwtData(['user' =>'Joao'], 60);

//print_r($jwt);

$return = $easyJwt->generateToken($jwt);

echo $return;
