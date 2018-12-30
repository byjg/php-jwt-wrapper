<?php

use ByJG\Util\JwtWrapper;
use PHPUnit\Framework\TestCase;

class JwtWrapperTest extends TestCase
{

    /**
     * @var JwtWrapper
     */
    protected $object;

    protected $dataToToken = ["name" => "John", "id"=>"1"];
    protected $server = "example.com";
    protected $secret = "secrect_key_for_test";

    protected function setUp()
    {
        unset($_SERVER["HTTP_AUTHORIZATION"]);
        $this->object = new JwtWrapper($this->server, $this->secret);
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

        $jwtWrapper = new JwtWrapper("otherserver.com", $this->secret);

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

        $jwtWrapper = new JwtWrapper($this->server, "some_creepy_secret");

        $jwtWrapper->extractData($token);
    }

    /**
     * @throws \ByJG\Util\JwtWrapperException
     * @expectedException \ByJG\Util\JwtWrapperException
     */
    public function testGetEmptyAuthorizationBearer()
    {
        $this->object->extractData();
    }

    public function testGenerateToken()
    {

    }
}
