<?php

namespace Teste;

use Firebase\JWT\JWT;

class EasyJwt
{
    const SECRET_KEY = "Y2hhdmViaW7DoXJpYQ==";
    const SERVER_NAME = "api.myserver.com";

    /**
     * @param $data
     * @param int $secondsNotBefore In Seconds
     * @param int $secondsExpire In Seconds
     * @return array
     */
    public function createToken($data, $secondsNotBefore = 10, $secondsExpire = 60)
    {
        $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $secondsNotBefore;          //Adding 10 seconds
        $expire     = $notBefore + $secondsExpire;            // Adding 60 seconds
        $serverName = EasyJwt::SERVER_NAME;         // Retrieve the server name from config file

        /*
         * Create the token as an array
         */
        $jwt = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => $data              // Data related to the signer user
        ];

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
    public function encodeJwt($data)
    {
        $secretKey = base64_decode(EasyJwt::SECRET_KEY);

        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return $jwt;
    }

    public function decodeJwt()
    {
        $authorization = isset($_SERVER[HTTP_AUTHORIZATION]) ? $_SERVER[HTTP_AUTHORIZATION] : "";
        list($jwt) = sscanf( $authorization, 'Bearer %s');

        if (empty($jwt)) {
            throw new \Exception('Invalid authorization token');
        }

        $secretKey = base64_decode(self::SECRET_KEY);
        $token = JWT::decode($jwt, $secretKey, array('HS512'));

        return $token;
    }
}