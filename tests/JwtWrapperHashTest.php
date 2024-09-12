<?php

namespace Test;

use ByJG\Util\JwtWrapper;
use ByJG\Util\JwtWrapperException;
use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use PHPUnit\Framework\TestCase;
use stdClass;
use UnexpectedValueException;

class JwtWrapperHashTest extends TestCase
{

    /**
     * @var JwtWrapper
     */
    protected $object;

    protected $dataToToken = ["name" => "John", "id"=>"1"];
    protected $server = "example.com";

    /**
     * @var \ByJG\Util\JwtKeyInterface
     */
    protected $jwtKey;

    protected function setUp(): void
    {
        $this->jwtKey = \ByJG\Util\JwtHashHmacSecret::getInstance("secrect_key_for_test", false);

        unset($_SERVER["HTTP_AUTHORIZATION"]);
        $this->object = new JwtWrapper($this->server, $this->jwtKey);
    }

    protected function tearDown(): void
    {
        $this->object = null;
        unset($_SERVER["HTTP_AUTHORIZATION"]);
    }


    public function testSuccessfulFlow1()
    {
        $jwt = $this->object->createJwtData($this->dataToToken);

        $this->assertEquals([
            JwtWrapper::IssuedAt    => $jwt[JwtWrapper::IssuedAt],  // Not deterministic for the test
            JwtWrapper::JsonTokenId => $jwt[JwtWrapper::JsonTokenId],  // Not deterministic for the test
            JwtWrapper::Issuer      => "example.com",
            JwtWrapper::NotBefore   => $jwt[JwtWrapper::IssuedAt],
            JwtWrapper::Expire      => $jwt[JwtWrapper::IssuedAt] + 60,
            'data' => $this->dataToToken
        ], $jwt);

        $token = $this->object->generateToken($jwt);

        $data = $this->object->extractData($token);

        $expectedData = new stdClass();
        $expectedData->iat = $jwt[JwtWrapper::IssuedAt];  // Not deterministic for the test
        $expectedData->jti = $jwt[JwtWrapper::JsonTokenId];  // Not deterministic for the test
        $expectedData->iss = "example.com";
        $expectedData->nbf = $jwt[JwtWrapper::IssuedAt];
        $expectedData->exp = $jwt[JwtWrapper::IssuedAt] + 60;
        $expectedData->data = (object)$this->dataToToken;

        $this->assertEquals(
            $expectedData,
            $data
        );

    }

    public function testSuccessfulFlow2()
    {
        $jwt = $this->object->createJwtData($this->dataToToken);

        $this->assertEquals([
            JwtWrapper::IssuedAt    => $jwt[JwtWrapper::IssuedAt],  // Not deterministic for the test
            JwtWrapper::JsonTokenId => $jwt[JwtWrapper::JsonTokenId],  // Not deterministic for the test
            JwtWrapper::Issuer      => "example.com",
            JwtWrapper::NotBefore   => $jwt[JwtWrapper::IssuedAt],
            JwtWrapper::Expire      => $jwt[JwtWrapper::IssuedAt] + 60,
            'data' => $this->dataToToken
        ], $jwt);

        $token = $this->object->generateToken($jwt);

        $_SERVER["HTTP_AUTHORIZATION"] = "Bearer $token";

        $data = $this->object->extractData();

        $expectedData = new stdClass();
        $expectedData->iat = $jwt[JwtWrapper::IssuedAt];  // Not deterministic for the test
        $expectedData->jti = $jwt[JwtWrapper::JsonTokenId];  // Not deterministic for the test
        $expectedData->iss = "example.com";
        $expectedData->nbf = $jwt[JwtWrapper::IssuedAt];
        $expectedData->exp = $jwt[JwtWrapper::IssuedAt] + 60;
        $expectedData->data = (object)$this->dataToToken;

        $this->assertEquals(
            $expectedData,
            $data
        );

    }

    public function testSuccessfulFlowSubject()
    {
        $jwt = $this->object->createJwtData(
            array_merge($this->dataToToken, [JwtWrapper::Issuer => "new_issuer", JwtWrapper::Subject => "userid"]),
            60,
            0,
            null
        );

        $this->assertEquals([
            JwtWrapper::IssuedAt    => $jwt[JwtWrapper::IssuedAt],  // Not deterministic for the test
            JwtWrapper::JsonTokenId => $jwt[JwtWrapper::JsonTokenId],  // Not deterministic for the test
            JwtWrapper::Issuer      => "new_issuer",
            JwtWrapper::Subject     => "userid",
            JwtWrapper::NotBefore   => $jwt[JwtWrapper::IssuedAt],
            JwtWrapper::Expire      => $jwt[JwtWrapper::IssuedAt] + 60,
            'name' => $this->dataToToken["name"],
            "id"   => $this->dataToToken["id"],
        ], $jwt);

        $token = $this->object->generateToken($jwt);

        $data = $this->object->extractData($token, false);

        $expectedData = new stdClass();
        $expectedData->iat = $jwt[JwtWrapper::IssuedAt];  // Not deterministic for the test
        $expectedData->jti = $jwt[JwtWrapper::JsonTokenId];  // Not deterministic for the test
        $expectedData->iss = "new_issuer";
        $expectedData->sub = "userid";
        $expectedData->nbf = $jwt[JwtWrapper::IssuedAt];
        $expectedData->exp = $jwt[JwtWrapper::IssuedAt] + 60;
        $expectedData->name = $this->dataToToken["name"];
        $expectedData->id = $this->dataToToken["id"];

        $this->assertEquals(
            $expectedData,
            $data
        );

    }

    public function testTokenWrongServerSameSecret()
    {
        $this->expectException(JwtWrapperException::class);

        $jwt = $this->object->createJwtData($this->dataToToken);
        $token = $this->object->generateToken($jwt);

        $jwtWrapper = new JwtWrapper("otherserver.com", $this->jwtKey);

        $jwtWrapper->extractData($token);
    }

    public function testTokenWrongSecret()
    {
        $this->expectException(SignatureInvalidException::class);

        $jwt = $this->object->createJwtData($this->dataToToken);
        $token = $this->object->generateToken($jwt);

        $jwtWrapper = new JwtWrapper($this->server, new \ByJG\Util\JwtHashHmacSecret("some_creepy_secret", true));

        $jwtWrapper->extractData($token);
    }

    public function testExpiredToken()
    {
        $this->expectException(ExpiredException::class);

        $jwt = $this->object->createJwtData($this->dataToToken,1);
        $token = $this->object->generateToken($jwt);

        sleep(2);

        $this->object->extractData($token);
    }

    public function testNotBeforeToken()
    {
        $this->expectException(BeforeValidException::class);

        $jwt = $this->object->createJwtData($this->dataToToken,60, 60);
        $token = $this->object->generateToken($jwt);

        $this->object->extractData($token);
    }

    public function testGetEmptyAuthorizationBearer()
    {
        $this->expectException(JwtWrapperException::class);

        $this->object->extractData();
    }

    public function testGetInvalidTokenSequence()
    {
        $this->expectException(UnexpectedValueException::class);

        $this->object->extractData("invalidtoken");
    }

    public function testGetInvalidToken()
    {
        $this->expectException(DomainException::class);

        $this->object->extractData("invalidtoken.hasthree.parts");
    }
}
