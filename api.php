<?php
/**
 * Created by PhpStorm.
 * User: jg
 * Date: 03/05/16
 * Time: 12:07
 */

require "vendor/autoload.php";

$easyJwt = new \Teste\EasyJwt();

$tokenDecomposed = $easyJwt->decodeJwt();

echo "{ 'data': 'value' }";
