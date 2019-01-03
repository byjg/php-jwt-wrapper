<?php

namespace ByJG\Util;

interface JwtKeyInterface
{
    public function getPublicKey();
    public function getPrivateKey();
    public function getAlghoritm();
}
