<?php
/**
 * Created by PhpStorm.
 * User: jg
 * Date: 03/05/16
 * Time: 12:07
 */

require "vendor/autoload.php";

$easyJwt = new \Teste\EasyJwt();

$jwt = $easyJwt->createToken(['user'=>'Joao'], 0, 60);

//print_r($jwt);

$return = $easyJwt->encodeJwt($jwt);

echo $return;