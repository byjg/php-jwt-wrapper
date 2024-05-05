<?php

namespace ByJG\Util;

class JwtOpenSSLKey implements JwtKeyInterface
{
    use JwtAlgorithmTrait;

    protected $private;
    protected $public;

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
     * @param $private
     * @param $public
     * @return JwtOpenSSLKey
     */
    public static function getInstance($private, $public, $algorithm = 'RS512')
    {
        return new JwtOpenSSLKey($private, $public, $algorithm);
    }

    public function getPublicKey()
    {
        return $this->public;
    }

    public function getPrivateKey()
    {
        return $this->private;
    }
}
