# JWT Wrapper

A simple and flexible wrapper around the Firebase JWT library that makes JWT token handling easy and intuitive in PHP applications.

[![Build Status](https://github.com/byjg/php-jwt-wrapper/actions/workflows/phpunit.yml/badge.svg?branch=master)](https://github.com/byjg/php-jwt-wrapper/actions/workflows/phpunit.yml)
[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg-success.svg)](http://opensource.byjg.com)
[![GitHub source](https://img.shields.io/badge/Github-source-informational?logo=github)](https://github.com/byjg/php-jwt-wrapper/)
[![GitHub license](https://img.shields.io/github/license/byjg/php-jwt-wrapper.svg)](https://opensource.byjg.com/opensource/licensing.html)
[![GitHub release](https://img.shields.io/github/release/byjg/php-jwt-wrapper.svg)](https://github.com/byjg/php-jwt-wrapper/releases/)

## Features

- **Simple API**: Create and validate JWT tokens with minimal code
- **Flexible Signing**: Support for both HMAC (shared secret) and RSA/ECDSA (public/private key) methods
- **Automatic Claims**: Built-in handling of standard JWT claims (iat, exp, nbf)
- **HTTP Integration**: Helper methods for extracting tokens from HTTP headers
- **Key Management**: Intuitive interfaces for different key types

## Installation

```bash
composer require "byjg/jwt-wrapper"
```

## Quick Example

```php
// Create a JWT token using HMAC
$server = "example.com";
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

// Add custom data and set expiration
$token = $jwtWrapper->generateToken(
    $jwtWrapper->createJwtData(["userId" => 123], 3600)
);

// Validate and extract data
try {
    $jwtData = $jwtWrapper->extractData($token);
    $userId = $jwtData->data->userId;
} catch (\ByJG\JwtWrapper\JwtWrapperException $e) {
    // Handle invalid token
}
```

## Documentation

Detailed documentation:

| Document                                       | Description                             |
|------------------------------------------------|-----------------------------------------|
| [Overview](docs/overview.md)                   | Introduction and core concepts          |
| [Key Types](docs/key-types.md)                 | HMAC and OpenSSL key configuration      |
| [Creating Tokens](docs/creating-tokens.md)     | Token generation and customization      |
| [Validating Tokens](docs/validating-tokens.md) | Token validation and data extraction    |
| [API Reference](docs/api-reference.md)         | Complete class and method documentation |


## Examples

The library includes complete examples in the `example` directory showing:

- Token creation with login.php
- Token validation with api.php
- Client-side usage with client.html

```mermaid
sequenceDiagram
    participant LOCAL
    participant CLIENT
    participant SERVER
    participant PRIVATE_RESOURCE
    LOCAL->>CLIENT: Retrieve Local Token
    CLIENT->>SERVER: Pass Token
    SERVER->>PRIVATE_RESOURCE: Validate Token
    PRIVATE_RESOURCE->>CLIENT: Return Result if token is valid
    CLIENT->>LOCAL: Store Token
```

## Running the tests

```bash
vendor/bin/phpunit
```

## Dependencies

```mermaid  
flowchart TD  
    byjg/jwt-wrapper --> firebase/php-jwt
    byjg/jwt-wrapper --> ext-openssl
```

----
[Open source ByJG](http://opensource.byjg.com)

