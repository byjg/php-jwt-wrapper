<?php

namespace ByJG\JwtWrapper;

use Firebase\JWT\JWT;
use InvalidArgumentException;

trait JwtAlgorithmTrait
{
    protected string $algorithm;
    protected string $algorithmType;
    protected array $availableAlgorithms = [];

    protected function setAlgorithmType(string $type): void
    {
        if (!in_array($type, ['hash_hmac', 'openssl'])) {
            throw new InvalidArgumentException("Invalid algorithm type");
        }
        $this->algorithmType = $type;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function setAlgorithm(string $algorithm): void
    {
        if (!in_array($algorithm, $this->availableAlgorithm())) {
            throw new InvalidArgumentException("Algorithm not supported");
        }
        $this->algorithm = $algorithm;
    }

    public function availableAlgorithm(): array
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