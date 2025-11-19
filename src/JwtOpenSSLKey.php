<?php

namespace ByJG\JwtWrapper;

use Override;

class JwtOpenSSLKey implements JwtKeyInterface
{
    use JwtAlgorithmTrait;

    protected string $private;
    protected string $public;

    /**
     * JwtRsaKey constructor.
     * @param string $private
     * @param string $public
     * @param string $algorithm
     */
    public function __construct(string $private, string $public, string $algorithm = 'RS512')
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

    #[Override]
    public function getPublicKey(): string
    {
        return $this->public;
    }

    #[Override]
    public function getPrivateKey(): string
    {
        return $this->private;
    }
}
