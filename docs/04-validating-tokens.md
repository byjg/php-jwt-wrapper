# Validating and Extracting JWT Tokens

Once you've created and distributed JWT tokens, you'll need to validate them when clients make requests to your server. This document explains how to validate and extract data from JWT tokens.

## Validating and Extracting Data

The library provides an easy way to validate tokens and extract their payload:

```php
<?php
// Initialize the wrapper with the same server name and key used for creation
$server = "example.com";
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

try {
    // Extract and validate data from the token
    $jwtData = $jwtWrapper->extractData($tokenString);
    
    // Access data from the token
    $userId = $jwtData->data->userId;
    $role = $jwtData->data->role;
    
    // The token is valid, proceed with the request
    
} catch (\ByJG\JwtWrapper\JwtWrapperException $e) {
    // Token is invalid, expired, or has been tampered with
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
```

## Automatic Extraction from HTTP Headers

The library can automatically extract the token from the `Authorization` header:

```php
<?php
// Assuming the client sent: Authorization: Bearer eyJhbGciO...

try {
    // Automatically extracts token from headers
    $jwtData = $jwtWrapper->extractData();
    
    // Process the data...
    
} catch (\ByJG\JwtWrapper\JwtWrapperException $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
```

## Issuer Validation

By default, the library validates that the token's issuer (`iss` claim) matches the server name provided during initialization. You can disable this validation if needed:

```php
// Disable issuer validation
$jwtData = $jwtWrapper->extractData($tokenString, false);
```

## Clock Skew Handling

Sometimes there might be clock differences between the server that created the token and the server validating it. You can configure a leeway period to account for this:

```php
// Set a 60-second leeway (tolerance) for time-based validations
$jwtWrapper->setLeeway(60);

// Proceed with token validation
$jwtData = $jwtWrapper->extractData($tokenString);
```

**Important Note**: The leeway is set as a static property in the underlying Firebase JWT library. Setting it will affect all instances of the JwtWrapper class.

## Complete Example

Here's a complete example of token validation:

```php
<?php
// Initialize the wrapper
$server = "example.com";
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));
$jwtWrapper = new \ByJG\JwtWrapper\JwtWrapper($server, $secret);

// Optional: Set a leeway for clock skew
$jwtWrapper->setLeeway(30);

try {
    // Method 1: Extract from Authorization header
    $jwtData = $jwtWrapper->extractData();
    
    // Method 2: Extract from a specific token string
    // $jwtData = $jwtWrapper->extractData($tokenString);
    
    // Method 3: Disable issuer validation
    // $jwtData = $jwtWrapper->extractData($tokenString, false);
    
    // Access data based on how the token was created
    // If created with a payload key (e.g., "data")
    $userId = $jwtData->data->userId;
    
    // If created without a payload key
    // $userId = $jwtData->userId;
    
    // Additional standard claims are accessible
    $issuedAt = $jwtData->iat;
    $issuer = $jwtData->iss;
    $expiration = $jwtData->exp;
    
    // Process the authenticated request
    echo json_encode(['success' => true, 'userId' => $userId]);
    
} catch (\ByJG\JwtWrapper\JwtWrapperException $e) {
    // Token validation failed
    http_response_code(401);
    echo json_encode(['error' => 'Authentication failed', 'message' => $e->getMessage()]);
    exit;
} catch (\Exception $e) {
    // Other errors
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
    exit;
}
``` 