<?php

class MyWrapper
{
    /**
     * Note --> Secret key must be stored at server side. Never Share this to the client HTML
     *
     * @return \ByJG\JwtWrapper\JwtWrapper
     * @throws \ByJG\JwtWrapper\JwtWrapperException
     */
    public static function getWrapper()
    {
        $server = "example.com";
        $secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("secrect_key_for_test"));

        $jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

        return $jwtWrapper;
    }

}

