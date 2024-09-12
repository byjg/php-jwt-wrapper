<?php

namespace ByJG\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class JwtWrapper
{
    const IssuedAt = 'iat';
    const JsonTokenId = 'jti';
    const Issuer = 'iss';
    const NotBefore = 'nbf';
    const Expire = 'exp';
    const Subject = 'sub';

    protected string $serverName;

    /**
     * @var JwtKeyInterface
     */
    protected JwtKeyInterface $jwtKey;

    /**
     * JwtWrapper constructor.
     * @param string $serverName
     * @param JwtKeyInterface $jwtKey
     */
    public function __construct(string $serverName, JwtKeyInterface $jwtKey)
    {
        $this->serverName = $serverName;
        $this->jwtKey = $jwtKey;
    }

    /**
     * @param array $data
     * @param int $secondsExpire In Seconds
     * @param int $secondsNotBefore In Seconds
     * @return array
     */
    public function createJwtData(array $data, int $secondsExpire = 60, int $secondsNotBefore = 0, ?string $payloadKey = "data"): array
    {
        $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $secondsNotBefore;          //Adding 10 seconds
        $expire     = $notBefore + $secondsExpire;            // Adding 60 seconds
        $serverName = $this->serverName;                       // Retrieve the server name from config file

        if (!empty($payloadKey)) {
            $data = [$payloadKey => $data];
        }
        /*
         * Create the token as an array
         */
        return array_merge(
            [
                JwtWrapper::IssuedAt  => $issuedAt,     // Issued at: time when the token was generated
                JwtWrapper::JsonTokenId  => $tokenId,      // Json Token Id: an unique identifier for the token
                JwtWrapper::Issuer  => $serverName,   // Issuer
                JwtWrapper::NotBefore  => $notBefore,    // Not before
                JwtWrapper::Expire  => $expire,       // Expire
            ],
            $data                            // Data related to the signer user
        );
    }

    public function generateToken(array $jwtData): string
    {
        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        return JWT::encode(
            $jwtData,      //Data to be encoded in the JWT
            $this->jwtKey->getPrivateKey(), // The signing key
            $this->jwtKey->getAlgorithm()
        );
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
     * @param string|null $bearer
     * @param bool $enforceIssuer
     * @return stdClass
     * @throws JwtWrapperException
     */
    public function extractData(?string $bearer = null, bool $enforceIssuer = true): stdClass
    {
        if (empty($bearer)) {
            $bearer = $this->getAuthorizationBearer();
        }

        $jwtData = JWT::decode(
            $bearer,
            new Key($this->jwtKey->getPublicKey(), $this->jwtKey->getAlgorithm())
        );

        if ($enforceIssuer && isset($jwtData->iss) && $jwtData->iss != $this->serverName) {
            throw new JwtWrapperException("Issuer does not match");
        }

        return $jwtData;
    }

    /**
     * @return mixed
     * @throws JwtWrapperException
     */
    public function getAuthorizationBearer(): string
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? "";
        list($bearer) = sscanf($authorization, 'Bearer %s');

        if (empty($bearer)) {
            throw new JwtWrapperException('Absent authorization token');
        }

        return $bearer;
    }

    public static function generateSecret(int $bytes): string
    {
        return base64_encode(openssl_random_pseudo_bytes($bytes));
    }
    
    /**
     * @param int $seconds A value no more than few minutes (in seconds) e.g. 60
     * @see: https://datatracker.ietf.org/doc/html/rfc7519#section-4.1.4
     */
    public function setLeeway(int $seconds): void
    {
        JWT::$leeway = $seconds;
    }
    
    public function getLeeway(): int
    {
        return JWT::$leeway;
    }
}
