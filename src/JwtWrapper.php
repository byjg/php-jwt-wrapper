<?php

namespace ByJG\Util;

use Firebase\JWT\JWT;

class JwtWrapper
{
    // Algorithm used to sign the token
    // @see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
    const CRYPTO_ALGHORITM = 'HS512';

    protected $secretKey;
    protected $serverName;

    public function __construct($serverName, $secretKey)
    {
        $this->serverName = $serverName;
        $this->secretKey = $secretKey;
    }

    /**
     * @param $data
     * @param int $secondsExpire In Seconds
     * @param int $secondsNotBefore In Seconds
     * @return array
     */
    public function createJwtData($data, $secondsExpire = 60, $secondsNotBefore = 0)
    {
        $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $secondsNotBefore;          //Adding 10 seconds
        $expire     = $notBefore + $secondsExpire;            // Adding 60 seconds
        $serverName = $this->secretKey;                       // Retrieve the server name from config file

        /*
         * Create the token as an array
         */
        return [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => $data              // Data related to the signer user
        ];
    }

    public function generateToken($jwtData)
    {
        $secretKey = base64_decode($this->secretKey);

        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        $jwt = JWT::encode(
            $jwtData,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            JwtWrapper::CRYPTO_ALGHORITM
        );

        return $jwt;
    }

    /*
    * Extract the key, which is coming from the config file.
    *
    * Best suggestion is the key to be a binary string and
    * store it in encoded in a config file.
    *
    * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
    *
    * keep it secure! You'll need the exact key to verify the
    * token later.
    */
    public function extractData($bearer = null)
    {
        if (empty($bearer)) {
            $bearer = $this->getAuthorizationBearer();
        }

        $secretKey = base64_decode($this->secretKey);
        $jwtData = JWT::decode(
            $bearer,
            $secretKey,
            [
                JwtWrapper::CRYPTO_ALGHORITM
            ]
        );

        return $jwtData;
    }

    public function getAuthorizationBearer()
    {
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : "";
        list($bearer) = sscanf($authorization, 'Bearer %s');

        if (empty($bearer)) {
            throw new \Exception('Absent authorization token');
        }

        return $bearer;
    }
}