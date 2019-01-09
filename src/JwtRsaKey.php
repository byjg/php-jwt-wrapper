<?php

namespace ByJG\Util;

class JwtRsaKey implements JwtKeyInterface
{
    protected $private;
    protected $public;

    /**
     * JwtRsaKey constructor.
     * @param $private
     * @param $public
     */
    public function __construct($private, $public)
    {
        $this->private = $private;
        $this->public = $public;
    }

    /**
     * @param $private
     * @param $public
     * @return JwtRsaKey
     */
    public static function getInstance($private, $public)
    {
        return new JwtRsaKey($private, $public);
    }

    public function getPublicKey()
    {
        return $this->public;
    }

    public function getPrivateKey()
    {
        return $this->private;
    }

    public function getAlghoritm()
    {
        return 'RS512';
    }
}
