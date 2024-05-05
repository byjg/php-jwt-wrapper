<?php

namespace ByJG\Util;

class JwtHashHmacSecret implements JwtKeyInterface
{
    use JwtAlgorithmTrait;

    protected $key;

    /**
     * JwtKeySecret constructor.
     * @param $key
     * @param bool $decode
     */
    public function __construct($key, bool $decode = true, $algorithm = 'HS512')
    {
        $this->key = ($decode ? base64_decode($key) : $key);
        $this->setAlgorithmType('hash_hmac');
        $this->setAlgorithm($algorithm);
    }

    /**
     * @param $key
     * @param bool $decode
     * @return JwtHashHmacSecret
     */
    public static function getInstance($key, bool $decode = true, $algorithm = 'HS512')
    {
        return new JwtHashHmacSecret($key, $decode, $algorithm);
    }

    public function getPublicKey()
    {
        return $this->key;
    }

    public function getPrivateKey()
    {
        return $this->key;
    }
}
