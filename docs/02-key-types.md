---
sidebar_position: 2
---

# Key Types

The PHP JWT Wrapper supports two types of keys for signing and validating JWT tokens:

1. **HMAC Secret Keys**: Faster but less secure
2. **OpenSSL Keys**: More secure but requires more overhead

## HMAC Secret Keys

HMAC keys use a shared secret for both signing and verification. This is a simpler approach but requires the same secret to be available on both the signing and verifying sides.

### Creating an HMAC Secret Key

```php title="Creating HMAC Secret Key"
// Option 1: Create from a base64-encoded string
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret(base64_encode("your_secret_key"));

// Option 2: Create from an already base64-encoded string
$secret = new \ByJG\JwtWrapper\JwtHashHmacSecret("base64_encoded_secret", false);

// Option 3: Using the static factory method
$secret = \ByJG\JwtWrapper\JwtHashHmacSecret::getInstance("your_secret_key");
```

### Generating a Secure HMAC Secret

For production use, you should generate a secure random key:

```php title="Generating Secure Secret"
// Using the helper method
$secretValue = \ByJG\JwtWrapper\JwtWrapper::generateSecret(64);

// Or using OpenSSL directly
$secretValue = base64_encode(openssl_random_pseudo_bytes(64));
```

## OpenSSL Keys

OpenSSL keys use a public/private key pair. The private key is used for signing tokens, and the public key is used for verification. 
This approach is more secure as the verifying party only needs the public key, not the private key.

### Creating an OpenSSL Key Pair

```php title="Creating OpenSSL Key Pair"
// Option 1: Create from private and public key strings
$jwtKey = new \ByJG\JwtWrapper\JwtOpenSSLKey($privateKeyString, $publicKeyString);

// Option 2: Using the static factory method
$jwtKey = \ByJG\JwtWrapper\JwtOpenSSLKey::getInstance($privateKeyString, $publicKeyString);
```

### Generating OpenSSL Key Pairs

You can generate an RSA key pair using the following commands:

```bash title="Generate RSA Key Pair"
# Generate private key
ssh-keygen -t rsa -C "Jwt RSA Key" -b 2048 -f private.pem

# Extract public key
openssl rsa -in private.pem -outform PEM -pubout -out public.pem
```

:::caution
When generating keys, do not set a password for the private key if it will be used in automated processes.
:::

## Available Algorithms

The library supports the following algorithms:

### HMAC Algorithms
- HS256
- HS384 
- HS512 (default)

### OpenSSL Algorithms
- RS256
- RS384
- RS512 (default)
- ES256
- ES384
- ES512
- PS256
- PS384
- PS512

To set a specific algorithm:

```php title="Setting Algorithm"
$hmacSecret = new \ByJG\JwtWrapper\JwtHashHmacSecret($secret, true, 'HS384');
$rsaKey = new \ByJG\JwtWrapper\JwtOpenSSLKey($privateKey, $publicKey, 'RS384');
```

## See Also

- [Creating Tokens](03-creating-tokens.md) - Learn how to use these keys to create JWT tokens
- [Validating Tokens](04-validating-tokens.md) - Understand how to validate tokens using these keys 