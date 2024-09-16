<?php

namespace ByJG\JwtWrapper;

class JwtOpenSSLKey implements JwtKeyInterface
{
    use JwtAlgorithmTrait;

    protected string $private;
    protected string $public;

    /**
     * JwtRsaKey constructor.
     * @param $private
     * @param $public
     */
    public function __construct($private, $public, $algorithm = 'RS512')
    {
        $this->private = $private;
        $this->public = $public;
        $this->setAlgorithmType('openssl');
        $this->setAlgorithm($algorithm);
    }

    /**
     * @param string $private
     * @param string $public
     * @param string $algorithm
     * @return JwtOpenSSLKey
     */
    public static function getInstance(string $private, string $public, string $algorithm = 'RS512'): JwtOpenSSLKey
    {
        return new JwtOpenSSLKey($private, $public, $algorithm);
    }

    public function getPublicKey(): string
    {
        return $this->public;
    }

    public function getPrivateKey(): string
    {
        return $this->private;
    }
}
