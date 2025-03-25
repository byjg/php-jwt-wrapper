---
sidebar_position: 3
---

# Creating JWT Tokens

The JWT Wrapper makes it easy to create JWT tokens with proper claim structures. This document explains how to create tokens using the library.

## Basic Token Creation

Creating a JWT token is a two-step process:

1. **Create JWT Data**: Generate the payload data with standard JWT claims
2. **Generate Token**: Convert the data into a signed JWT token string

### Step 1: Initialize JwtWrapper

First, initialize the JWT wrapper with your server name and key:

```php
// Using HMAC
$server = "example.com";  // Your server name (issuer)
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

// Or using OpenSSL
$server = "example.com";
$jwtKey = new \ByJG\JwtWrapper\JwtOpenSSLKey($privateKeyString, $publicKeyString);
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $jwtKey);
```

### Step 2: Create JWT Data

Use the `createJwtData` method to create a payload with standard JWT claims:

```php
$jwtData = $jwtWrapper->createJwtData(
    [
        "userId" => 123,
        "role" => "admin"
    ],
    3600,        // Expiration time in seconds (default: 60)
    0,           // Not Before time offset in seconds (default: 0)
    "payload"    // Optional key to wrap your data (default: "data")
);
```

The resulting `$jwtData` will be an array containing:

```php
[
    "iat" => 1234567890,              // Issued At timestamp
    "jti" => "base64_random_token_id", // JWT ID
    "iss" => "example.com",           // Issuer (your server name)
    "nbf" => 1234567890,              // Not Before timestamp 
    "exp" => 1234571490,              // Expiration timestamp
    "payload" => [                    // Your custom data
        "userId" => 123,
        "role" => "admin"
    ]
]
```

If you set the `$payloadKey` parameter to `null`, your data will not be wrapped:

```php
$jwtData = $jwtWrapper->createJwtData(
    [
        "userId" => 123,
        "role" => "admin"
    ],
    3600,
    0,
    null  // No wrapping key
);
```

This will result in:

```php
[
    "iat" => 1234567890,
    "jti" => "base64_random_token_id",
    "iss" => "example.com",
    "nbf" => 1234567890,
    "exp" => 1234571490,
    "userId" => 123,
    "role" => "admin"
]
```

### Step 3: Generate Token

Convert the JWT data into a signed token string:

```php
$token = $jwtWrapper->generateToken($jwtData);
```

The `$token` is now a string that can be sent to clients, stored in cookies, etc.

## Complete Example

```php
<?php
// Initialize the wrapper
$server = "example.com";
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

// Create JWT data
$jwtData = $jwtWrapper->createJwtData(
    [
        "userId" => 123,
        "email" => "user@example.com"
    ],
    3600,   // Expire in 1 hour
    30      // Not valid before 30 seconds from now
);

// Generate token
$token = $jwtWrapper->generateToken($jwtData);

// The token can now be sent to the client
echo $token;
```

## See Also

- [Validating Tokens](04-validating-tokens.md) - Learn how to validate the tokens you create
- [API Reference](05-api-reference.md) - Detailed documentation of all available methods 