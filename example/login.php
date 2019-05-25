<?php
require "vendor/autoload.php";

require_once "include.php";

$easyJwt = MyWrapper::getWrapper();

$jwt = $easyJwt->createJwtData(['user' =>'Joao'], 60);

//print_r($jwt);

$return = $easyJwt->generateToken($jwt);

echo $return;
