<?php

namespace ByJG\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtWrapper
{

    protected $serverName;

    /**
     * @var JwtKeyInterface
     */
    protected $jwtKey;

    /**
     * JwtWrapper constructor.
     * @param string $serverName
     * @param JwtKeyInterface $jwtKey
     * @throws JwtWrapperException
     */
    public function __construct($serverName, $jwtKey)
    {
        $this->serverName = $serverName;
        $this->jwtKey = $jwtKey;

        if (!($jwtKey instanceof JwtKeyInterface)) {
            throw new JwtWrapperException('Constructor needs to receive a JwtKeyInterface');
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
            $this->jwtKey->getPrivateKey(), // The signing key
            $this->jwtKey->getAlgorithm()
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
            new Key($this->jwtKey->getPublicKey(), $this->jwtKey->getAlgorithm())
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

    public static function generateSecret($bytes)
    {
        return base64_encode(openssl_random_pseudo_bytes($bytes));
    }
    
    /**
     * @param int $seconds A value no more than few minutes (in seconds) e.g. 60
     * @see: https://datatracker.ietf.org/doc/html/rfc7519#section-4.1.4
     */
    public function setLeeway($seconds)
    {
        JWT::$leeway = $seconds;
    }
    
    public function getLeeway()
    {
        return JWT::$leeway;
    }
}
