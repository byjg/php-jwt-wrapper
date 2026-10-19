<?php

namespace Example\Classes;

use ByJG\JwtWrapper\JwtHashHmacSecret;
use ByJG\JwtWrapper\JwtWrapper;
use ByJG\JwtWrapper\JwtWrapperException;

class MyWrapper
{
    /**
     * Note --> Secret key must be stored at server side. Never Share this to the client HTML
     *
     * @return JwtWrapper
     */
    public static function getWrapper()
    {
        $server = "example.com";
        $secret = new JwtHashHmacSecret("c2VjcmV0S2V5X3RoaXNfaXNfYV92ZXJ5X2xvbmdfc2VjcmV0X2tleV90aGF0X2lzXzY0X2J5dGVzX2xvbmchIQ==");

        return new JwtWrapper($server, $secret);
    }

}

