<?php
require "vendor/autoload.php";

// Note --> Secret key must be stored at server side. Is not a good idea has to fixed on the code
// There are 2 places here where it is called
$easyJwt = new \ByJG\Util\JwtWrapper('api.test.com', '5pbZNksFl4yhr6qUNnv/FyfPP3vbYkO8arGtuEX+EIU=');

$jwt = $easyJwt->createJwtData(['user' =>'Joao'], 60);

//print_r($jwt);

$return = $easyJwt->generateToken($jwt);

echo $return;
