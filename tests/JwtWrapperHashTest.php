<?php

use ByJG\Util\JwtWrapper;
use PHPUnit\Framework\TestCase;

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

    protected function setUp()
    {
        $this->jwtKey = \ByJG\Util\JwtKeySecret::getInstance("secrect_key_for_test", false);

        unset($_SERVER["HTTP_AUTHORIZATION"]);
        $this->object = new JwtWrapper($this->server, $this->jwtKey);
    }

    protected function tearDown()
    {
        $this->object = null;
        unset($_SERVER["HTTP_AUTHORIZATION"]);
    }


    public function testSuccessfulFlow1()
    {
        $jwt = $this->object->createJwtData($this->dataToToken);

        $this->assertEquals([
            'iat'  => $jwt["iat"],  // Not deterministic for the test
            'jti'  => $jwt["jti"],  // Not deterministic for the test
            'iss'  => "example.com",
            'nbf'  => $jwt["iat"],
            'exp'  => $jwt["iat"] + 60,
            'data' => $this->dataToToken
        ], $jwt);

        $token = $this->object->generateToken($jwt);

        $data = $this->object->extractData($token);

        $expectedData = new stdClass();
        $expectedData->iat = $jwt["iat"];  // Not deterministic for the test
        $expectedData->jti = $jwt["jti"];  // Not deterministic for the test
        $expectedData->iss = "example.com";
        $expectedData->nbf = $jwt["iat"];
        $expectedData->exp = $jwt["iat"] + 60;
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
            'iat'  => $jwt["iat"],  // Not deterministic for the test
            'jti'  => $jwt["jti"],  // Not deterministic for the test
            'iss'  => "example.com",
            'nbf'  => $jwt["iat"],
            'exp'  => $jwt["iat"] + 60,
            'data' => $this->dataToToken
        ], $jwt);

        $token = $this->object->generateToken($jwt);

        $_SERVER["HTTP_AUTHORIZATION"] = "Bearer $token";

        $data = $this->object->extractData();

        $expectedData = new stdClass();
        $expectedData->iat = $jwt["iat"];  // Not deterministic for the test
        $expectedData->jti = $jwt["jti"];  // Not deterministic for the test
        $expectedData->iss = "example.com";
        $expectedData->nbf = $jwt["iat"];
        $expectedData->exp = $jwt["iat"] + 60;
        $expectedData->data = (object)$this->dataToToken;

        $this->assertEquals(
            $expectedData,
            $data
        );

    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \ByJG\Util\JwtWrapperException
     */
    public function testTokenWrongServerSameSecret()
    {
        $jwt = $this->object->createJwtData($this->dataToToken);
        $token = $this->object->generateToken($jwt);

        $jwtWrapper = new JwtWrapper("otherserver.com", $this->jwtKey);

        $jwtWrapper->extractData($token);
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \Firebase\JWT\SignatureInvalidException
     */
    public function testTokenWrongSecret()
    {
        $jwt = $this->object->createJwtData($this->dataToToken);
        $token = $this->object->generateToken($jwt);

        $jwtWrapper = new JwtWrapper($this->server, new \ByJG\Util\JwtKeySecret("some_creepy_secret", true));

        $jwtWrapper->extractData($token);
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \Firebase\JWT\ExpiredException
     */
    public function testExpiredToken()
    {
        $jwt = $this->object->createJwtData($this->dataToToken,1);
        $token = $this->object->generateToken($jwt);

        sleep(2);

        $this->object->extractData($token);
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \Firebase\JWT\BeforeValidException
     */
    public function testNotBeforeToken()
    {
        $jwt = $this->object->createJwtData($this->dataToToken,60, 60);
        $token = $this->object->generateToken($jwt);

        $this->object->extractData($token);
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \ByJG\Util\JwtWrapperException
     */
    public function testGetEmptyAuthorizationBearer()
    {
        $this->object->extractData();
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException UnexpectedValueException
     */
    public function testGetInvalidTokenSequence()
    {
        $this->object->extractData("invalidtoken");
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException DomainException
     */
    public function testGetInvalidToken()
    {
        $this->object->extractData("invalidtoken.hasthree.parts");
    }
}
