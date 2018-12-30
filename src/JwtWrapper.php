<?php

namespace ByJG\Util;

use Firebase\JWT\JWT;

class JwtWrapper
{

    protected $cryptoAlghoritm;
    protected $secretKey;
    protected $publicKey;
    protected $serverName;

    public function __construct($serverName, $secretKey, $publicKey = null)
    {
        $this->serverName = $serverName;
        $this->secretKey = base64_decode($secretKey);
        $this->publicKey = $this->secretKey;
        $this->cryptoAlghoritm = 'HS512';
        if (!empty($publicKey)) {
            $this->cryptoAlghoritm = 'RS512';
            $this->secretKey = $secretKey;
            $this->publicKey = $publicKey;
        }
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
        $serverName = $this->serverName;                       // Retrieve the server name from config file

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
        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        $jwt = JWT::encode(
            $jwtData,      //Data to be encoded in the JWT
            $this->secretKey, // The signing key
            $this->cryptoAlghoritm
        );

        return $jwt;
    }

    /**
     * Extract the key, which is coming from the config file.
     *
     * Best suggestion is the key to be a binary string and
     * store it in encoded in a config file.
     *
     * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
     *
     * keep it secure! You'll need the exact key to verify the
     * token later.
     *
     * @param null $bearer
     * @return object
     * @throws JwtWrapperException
     */
    public function extractData($bearer = null)
    {
        if (empty($bearer)) {
            $bearer = $this->getAuthorizationBearer();
        }

        $jwtData = JWT::decode(
            $bearer,
            $this->publicKey,
            [
                $this->cryptoAlghoritm
            ]
        );

        if (isset($jwtData->iss) && $jwtData->iss != $this->serverName) {
            throw new JwtWrapperException("Issuer does not match");
        }

        return $jwtData;
    }

    /**
     * @return mixed
     * @throws JwtWrapperException
     */
    public function getAuthorizationBearer()
    {
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : "";
        list($bearer) = sscanf($authorization, 'Bearer %s');

        if (empty($bearer)) {
            throw new JwtWrapperException('Absent authorization token');
        }

        return $bearer;
    }
}