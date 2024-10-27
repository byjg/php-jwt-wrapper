<?php

namespace ByJG\JwtWrapper;

class JwtHashHmacSecret implements JwtKeyInterface
{
    use JwtAlgorithmTrait;

    protected string $key;

    /**
     * JwtKeySecret constructor.
     * @param string $key
     * @param bool $decode
     * @param string $algorithm
     */
    public function __construct(string $key, bool $decode = true, string $algorithm = 'HS512')
    {
        $this->key = ($decode ? base64_decode($key) : $key);
        $this->setAlgorithmType('hash_hmac');
        $this->setAlgorithm($algorithm);
    }

    /**
     * @param string $key
     * @param bool $decode
     * @param string $algorithm
     * @return JwtHashHmacSecret
     */
    public static function getInstance(string $key, bool $decode = true, string $algorithm = 'HS512'): JwtHashHmacSecret
    {
        return new JwtHashHmacSecret($key, $decode, $algorithm);
    }

    public function getPublicKey(): string
    {
        return $this->key;
    }

    public function getPrivateKey(): string
    {
        return $this->key;
    }
}
