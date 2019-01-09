<?php

namespace ByJG\Util;

class JwtKeySecret implements JwtKeyInterface
{

    protected $key;

    /**
     * JwtKeySecret constructor.
     * @param $key
     * @param bool $decode
     */
    public function __construct($key, $decode = true)
    {
        $this->key = ($decode ? base64_decode($key) : $key);
    }

    /**
     * @param $key
     * @param bool $decode
     * @return JwtKeySecret
     */
    public static function getInstance($key, $decode = true)
    {
        return new JwtKeySecret($key, $decode);
    }

    public function getPublicKey()
    {
        return $this->key;
    }

    public function getPrivateKey()
    {
        return $this->key;
    }

    public function getAlghoritm()
    {
        return 'HS512';
    }
}
