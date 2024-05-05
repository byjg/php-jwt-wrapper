<?php

namespace ByJG\Util;

interface JwtKeyInterface
{
    public function getPublicKey();
    public function getPrivateKey();
    public function getAlgorithm();
    public function setAlgorithm($algorithm);
    public function availableAlgorithm();
}
