<?php

namespace ByJG\JwtWrapper;

interface JwtKeyInterface
{
    public function getPublicKey(): string;
    public function getPrivateKey(): string;
    public function getAlgorithm(): string;
    public function setAlgorithm(string $algorithm): void;
    public function availableAlgorithm(): array;
}
