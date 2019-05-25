<?php

class MyWrapper
{
    /**
     * Note --> Secret key must be stored at server side. Never Share this to the client HTML
     *
     * @return \ByJG\Util\JwtWrapper
     * @throws \ByJG\Util\JwtWrapperException
     */
    public static function getWrapper()
    {
        $server = "example.com";
        $secret = new \ByJG\Util\JwtKeySecret(base64_encode("secrect_key_for_test"));

        $jwtWrapper = new \ByJG\Util\JwtWrapper($server, $secret);

        return $jwtWrapper;
    }

}

