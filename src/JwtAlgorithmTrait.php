<?php

namespace ByJG\Util;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

trait JwtAlgorithmTrait
{
    protected $algorithm = null;
    protected $algorithmType = null;
    protected $availableAlgorithms = [];

    protected function setAlgorithmType($type)
    {
        if (!in_array($type, ['hash_hmac', 'openssl'])) {
            throw new \InvalidArgumentException("Invalid algorithm type");
        }
        $this->algorithmType = $type;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setAlgorithm($algorithm)
    {
        if (!in_array($algorithm, $this->availableAlgorithm())) {
            throw new \InvalidArgumentException("Algorithm not supported");
        }
        $this->algorithm = $algorithm;
    }

    public function availableAlgorithm()
    {
        if (empty($this->availableAlgorithms)) {
            $algs = JWT::$supported_algs;
            foreach ($algs as $alg => $prop) {
                if ($prop[0] == $this->algorithmType) {
                    $this->availableAlgorithms[] = $alg;
                }
            }
        }

        return $this->availableAlgorithms;
    }
}