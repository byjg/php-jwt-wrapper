---
sidebar_position: 5
---

# API Reference

This document provides a detailed reference for all classes and methods in the PHP JWT Wrapper library.

## JwtWrapper Class

The main class for creating and validating JWT tokens.

### Constants

| Constant      | Value   | Description                                                                   |
|---------------|---------|-------------------------------------------------------------------------------|
| `IssuedAt`    | `'iat'` | Standard JWT claim for the time the token was issued                          |
| `JsonTokenId` | `'jti'` | Standard JWT claim for the unique identifier of the token                     |
| `Issuer`      | `'iss'` | Standard JWT claim for the issuer of the token                                |
| `NotBefore`   | `'nbf'` | Standard JWT claim for the time before which the token should not be accepted |
| `Expire`      | `'exp'` | Standard JWT claim for the expiration time                                    |
| `Subject`     | `'sub'` | Standard JWT claim for the subject of the token                               |

### Methods

#### `__construct(string $serverName, JwtKeyInterface $jwtKey)`

Creates a new JwtWrapper instance.

**Parameters:**
- `$serverName`: The name of the server (used as the issuer claim)
- `$jwtKey`: The key implementation to use for signing and verification

**Example:**
```php
$wrapper = new JwtWrapper('example.com', $jwtKey);
```

#### `createJwtData(array $data, int $secondsExpire = 60, int $secondsNotBefore = 0, ?string $payloadKey = "data"): array`

Creates a JWT data array with standard claims.

**Parameters:**
- `$data`: The custom data to include in the token
- `$secondsExpire`: Number of seconds until the token expires (default: 60)
- `$secondsNotBefore`: Number of seconds before the token becomes valid (default: 0)
- `$payloadKey`: Key to wrap the data under, or null for no wrapping (default: "data")

**Returns:**
- An array containing the JWT data with standard claims

**Example:**
```php
$jwtData = $wrapper->createJwtData(['userId' => 123], 3600, 0, 'payload');
```

#### `generateToken(array $jwtData): string`

Generates a JWT token string from the given data.

**Parameters:**
- `$jwtData`: The JWT data array (usually created by `createJwtData`)

**Returns:**
- A signed JWT token string

**Example:**
```php
$token = $wrapper->generateToken($jwtData);
```

#### `extractData(?string $bearer = null, bool $enforceIssuer = true): stdClass`

Extracts and validates data from a JWT token.

**Parameters:**
- `$bearer`: The JWT token string, or null to extract from HTTP headers (default: null)
- `$enforceIssuer`: Whether to validate the issuer claim (default: true)

**Returns:**
- A stdClass object containing the decoded token data

**Throws:**
- `JwtWrapperException`: If token validation fails

**Example:**
```php
$data = $wrapper->extractData($token);
```

#### `getAuthorizationBearer(): string`

Extracts the Bearer token from the HTTP Authorization header.

**Returns:**
- The Bearer token string

**Throws:**
- `JwtWrapperException`: If the Authorization header is missing or invalid

**Example:**
```php
$token = $wrapper->getAuthorizationBearer();
```

#### `static generateSecret(int $bytes): string`

Generates a random secret for use with HMAC algorithms.

**Parameters:**
- `$bytes`: The number of random bytes to generate

**Returns:**
- A base64-encoded random string

**Example:**
```php
$secret = JwtWrapper::generateSecret(64);
```

#### `setLeeway(int $seconds): void`

Sets the leeway time for token validation to account for clock skew.

**Parameters:**
- `$seconds`: The leeway in seconds

**Example:**
```php
$wrapper->setLeeway(30);
```

#### `getLeeway(): int`

Gets the current leeway setting.

**Returns:**
- The current leeway in seconds

**Example:**
```php
$leeway = $wrapper->getLeeway();
```

## JwtKeyInterface

Interface implemented by all key classes.

### Methods

#### `getPublicKey(): string`

Returns the public key or shared secret for token verification.

#### `getPrivateKey(): string`

Returns the private key or shared secret for token signing.

#### `getAlgorithm(): string`

Returns the current algorithm.

#### `setAlgorithm(string $algorithm): void`

Sets the algorithm to use.

#### `availableAlgorithm(): array`

Returns an array of available algorithms for the key type.

## JwtHashHmacSecret Class

Implementation of JwtKeyInterface for HMAC secrets.

### Methods

#### `__construct(string $key, bool $decode = true, string $algorithm = 'HS512')`

Creates a new HMAC secret key.

**Parameters:**
- `$key`: The secret key
- `$decode`: Whether to base64 decode the key (default: true)
- `$algorithm`: The HMAC algorithm to use (default: 'HS512')

**Example:**
```php
$secret = new JwtHashHmacSecret(base64_encode('my-secret'), true, 'HS256');
```

#### `static getInstance(string $key, bool $decode = true, string $algorithm = 'HS512'): JwtHashHmacSecret`

Static factory method to create a JwtHashHmacSecret instance.

## JwtOpenSSLKey Class

Implementation of JwtKeyInterface for OpenSSL (RSA/ECDSA) keys.

### Methods

#### `__construct(string $private, string $public, string $algorithm = 'RS512')`

Creates a new OpenSSL key pair.

**Parameters:**
- `$private`: The private key in PEM format
- `$public`: The public key in PEM format
- `$algorithm`: The RSA/ECDSA algorithm to use (default: 'RS512')

**Example:**
```php
$key = new JwtOpenSSLKey($privateKeyPem, $publicKeyPem, 'RS256');
```

#### `static getInstance(string $private, string $public, string $algorithm = 'RS512'): JwtOpenSSLKey`

Static factory method to create a JwtOpenSSLKey instance.

## JwtWrapperException Class

Exception thrown when JWT operations fail.

### Usage

```php
try {
    $data = $wrapper->extractData($token);
} catch (JwtWrapperException $e) {
    // Handle the exception
    echo $e->getMessage();
}
```

## See Also

- [Creating Tokens](03-creating-tokens.md) - Practical examples of using these API methods
- [Validating Tokens](04-validating-tokens.md) - Examples of token validation using the API 